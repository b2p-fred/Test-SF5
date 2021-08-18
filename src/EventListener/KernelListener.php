<?php

namespace App\EventListener;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class KernelListener
{
    private LoggerInterface $logger;

    private $id = null;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        $this->id = \uniqid('');

        /* To be completed :)
         * x-user-agent, see doc/code/x-user-agent.md
            if (null !== $request->headers->get('X-USER-AGENT') && ...///...) {
                // Client is a Web SPA
            } else {
                // Client is a mobile device
            }
         */

        $headers = $request->headers->all();

        // Obfuscate the JWT token in the header
        if (isset($headers['authorization'])) {
            unset($headers['authorization']);
            $headers['authorization'] = 'Bearer ---';
        }

        $this->logger->debug('request', [
            'id' => $this->id,
            'route' => $request->getMethod().' '.$request->getRequestUri(),
            'client' => $request->getClientIp(),
            'clients' => $request->getClientIps(),
            'request body' => $request->request->all(),
            'request payload' => $request->getContent(),
            'query' => $request->query->all(),
            'files' => $request->files->all(),
            'headers' => $headers,
        ]);
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $content = \json_decode($response->getContent(), true);

        // Obfuscate the JWT tokens in the data
        if (isset($content['token'])) {
            unset($content['token']);
            $content['token'] = '---';
        }
        if (isset($content['refresh_token'])) {
            unset($content['refresh_token']);
            $content['refresh_token'] = '---';
        }

        $this->logger->debug('response', [
            'id' => $this->id,
            'route' => $request->getMethod().' '.$request->getRequestUri(),
            'status' => $response->getStatusCode(),
            'response' => $content,
            'headers' => $response->headers->all(),
        ]);
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HttpException || $exception instanceof ValidationException) {
            return;
        }

        $this->logger->warning('Uncaught exception', [
            'exception' => \get_class($exception),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        $event->setResponse(new JsonResponse(
            [
                'code' => 400,
                'violations' => [
                    [
                        'message' => 'Application unexpected error: '.$exception->getMessage(),
                        'messageTemplate' => 'application.error',
                    ],
                ],
            ],
            400
        ));
    }
}
