<?php

namespace App\Controller;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

class BuildingController extends AbstractController
{
    /**
     * @Route("/admin/buildings", name="buildings")
     */
    public function index(BuildingRepository $buildingRepository): Response
    {
        $buildings = $buildingRepository->findAll();

        return $this->render('building/index.html.twig', [
            'items' => $buildings,
        ]);
    }

    /**
     * @Route("/admin/building/{id}", name="building_show")
     *
     * Thanks to the ExtraBundle, no need to get a Repository and find the building in the repository :)
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function show(Building $building): Response
    {
        return $this->render('building/show.html.twig', ['item' => $building]);
    }
}
