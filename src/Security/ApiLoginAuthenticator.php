<?php

namespace App\Security;

use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

/*
 * This authenticator get the login parameters either from FORM parameters or JSON body.
 */
class ApiLoginAuthenticator extends AbstractAuthenticator
{
    private LoggerInterface $logger;

    private UserRepository $userRepository;

    private ?array $requestCredentials;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool
    {
        $this->logger->debug('[ApiLoginAuthenticator] - supports, uri: '.$request->getUri());

        $this->requestCredentials = $this->getCredentialsFromRequest($request);

        $this->logger->debug('[ApiLoginAuthenticator] - supports: '.(null !== $this->requestCredentials ? 'Yes' : 'No!'));

        return null !== $this->requestCredentials;
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request): array
    {
        return $this->requestCredentials;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $this->logger->debug('[ApiLoginAuthenticator] - authenticate, user: '.$this->requestCredentials['email']);

        return new Passport(
            new UserBadge((string) $this->requestCredentials['email'], function ($userIdentifier) {
                return $this->userRepository->findOneBy(['email' => $userIdentifier]);
            }),
            new PasswordCredentials((string) $this->requestCredentials['password'])
        );
    }

    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
    {
        $this->logger->debug('[ApiLoginAuthenticator] - createAuthenticatedToken');

        return parent::createAuthenticatedToken($passport, $firewallName);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName): ?Response
    {
        $this->logger->debug('[ApiLoginAuthenticator] - onAuthenticationSuccess');

        $dataForResponse = [
            'uuid' => $token->getUserIdentifier(),
            'roles' => $token->getRoleNames(),
            'token' => $token->getCredentials(),
            'attributes' => $token->getAttributes(),
        ];

        return new JsonResponse($dataForResponse);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->logger->debug('[ApiLoginAuthenticator] - onAuthenticationFailure');

        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    private function getCredentialsFromRequest(Request $request): ?array
    {
        $this->logger->debug('[ApiLoginAuthenticator] - getCredentialsFromRequest');

        $email = null;
        $password = null;

        // Old form login (query parameters)
        if ($request->request->get('email') && $request->request->get('password')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $this->logger->debug('[ApiLoginAuthenticator] - got from form parameters: '.$email);
        }

        // For json login
        if (null === $email && !empty($request->getContent())) {
            $content = \json_decode($request->getContent(), true);
            if (!empty($content['email']) && !empty($content['password'])) {
                $email = $content['email'];
                $password = $content['password'];
                $this->logger->debug('[ApiLoginAuthenticator] - got from Json body: '.$email);
            }
        }

        return (null !== $email && null !== $password) ? ['email' => $email, 'password' => $password] : null;
    }
}
