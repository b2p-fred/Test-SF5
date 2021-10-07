<?php

namespace App\Security;

use App\Security\OAuthUser;
use Parroauth2\Client\ClientConfig;
use Parroauth2\Client\ClientInterface;
use Parroauth2\Client\Extension\JwtAccessToken\JwtAccessToken;
use Parroauth2\Client\Provider\ProviderConfigPool;
use Parroauth2\Client\Provider\ProviderLoader;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    private LoggerInterface $logger;

    private ClientInterface $client;

    private ?string $requestToken;

    public function __construct(LoggerInterface $logger, string $oAuthProviderUrl, string $oAuthClientId, string $oAuthClientSecret, CacheInterface $cache)
    {
        $this->logger = $logger;

        // Load the provider and provide a cache for the config to ensure that
        // keys and config are stored locally, and the server will not perform
        // any request to check the token
        $loader = new ProviderLoader(null, null, null, null, new ProviderConfigPool($cache));

        // Create the client
        $this->client = $loader->discover($oAuthProviderUrl)->client(
            (new ClientConfig($oAuthClientId))->setSecret($oAuthClientSecret)
        );

        // Enable local introspection using JWT access token
        $this->client->register(new JwtAccessToken());
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        $this->requestToken = $this->getCredentialsFromRequest($request);

        return null !== $this->requestToken;
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \Parroauth2\Client\Exception\UnsupportedServerOperation
     * @throws \Parroauth2\Client\Exception\Parroauth2Exception
     */
    public function authenticate(Request $request): PassportInterface
    {
        // Perform introspection on the received token
        // No HTTP request should be performed here because local introspection is enabled
        $response = $this->client->endPoints()->introspection()
            ->accessToken($this->requestToken)
            ->call();

        // The token is expired or invalid
        if (!$response->active()) {
            $this->logger->debug('[ApiKeyAuthenticator] - expired token!');

            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }
        $this->logger->debug('[ApiKeyAuthenticator] - got a token: '.$response->username());

        $token = $response;
//        $this->logger->debug('[ApiKeyAuthenticator] - token: '.serialize($token));

//        $user = new OAuthUser($this->logger, $response->username(), $response);
        $logger = $this->logger;

        return new SelfValidatingPassport(new UserBadge($response->username(), function () use ($logger, $response) {
            $this->logger->debug('[ApiKeyAuthenticator] - creating a user: '.$response->username());

            $user = new OAuthUser($logger, $response->username(), $response->claims());
            $this->logger->debug('[ApiKeyAuthenticator] - user: '.$user);

            return $user;
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Extract the token from the Authorization HTTP header
     *  'Authorization': 'Bearer XxX'.
     */
    private function getCredentialsFromRequest(Request $request): ?string
    {
        if (!$request->headers->has('Authorization')) {
            return null;
        }

        $authorizationHeader = $request->headers->get('Authorization');
        $headerParts = explode(' ', $authorizationHeader);

        if (!(2 === count($headerParts) && 0 === strcasecmp($headerParts[0], 'Bearer'))) {
            return null;
        }

        return $headerParts[1];
    }
}
