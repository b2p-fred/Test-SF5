<?php

// api/src/OpenApi/JwtDecorator.php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

final class JwtDecorator implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Error'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'code' => [
                    'type' => 'integer',
                    'example' => 401,
                    'readOnly' => true,
                ],
                'message' => [
                    'type' => 'string',
                    'example' => 'Invalid credentials',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'description' => 'The user unique and valid mail address',
                    'example' => 'glagaffe@edition-dupuis.com',
                ],
                'password' => [
                    'type' => 'string',
                    'description' => 'The user password',
                    'example' => 'Gaston',
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            'JWT Token',
            null, null, null, null,
            new Model\Operation(
                'postCredentialsItem',
                ['User authentication'],
                [
                    '200' => [
                        'description' => 'The requested JWT token is contained in the `token` attribute',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'The provided credentials do not allow to get a JWT',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Error',
                                ],
                            ],
                        ],
                    ],
                ],
                'Get JWT token to login.',
                'Description',
                null,
                [],
                new Model\RequestBody(
                    'Generate new JWT token',
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/login_check', $pathItem);

        return $openApi;
    }
}
