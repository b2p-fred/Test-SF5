<?php

namespace App\Action;

use App\Manager\ApiInformationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetVersionAction extends AbstractController
{
    protected ApiInformationManager $apiInformationManager;

    public function __construct(ApiInformationManager $apiInformationManager)
    {
        $this->apiInformationManager = $apiInformationManager;
    }

    /**
     * @Route("/api/version", name="api_version", methods={"GET"})
     */
    public function __invoke(): JsonResponse
    {
        $apiInfo = $this->apiInformationManager->getBaseConfiguration();

        return new JsonResponse($apiInfo);
    }
}
