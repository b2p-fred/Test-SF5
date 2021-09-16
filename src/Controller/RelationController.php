<?php

namespace App\Controller;

use App\Entity\Relation;
use App\Repository\RelationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RelationController extends AbstractController
{
    /**
     * @Route("/admin/companies", name="companies")
     */
    public function index(RelationRepository $relationRepository): Response
    {
        $companies = $relationRepository->findAll();

        return $this->render('relation/index.html.twig', [
            'items' => $companies,
        ]);
    }

    /**
     * @Route("/admin/relation/create", name="create_relation")
     */
    public function createRelation(ValidatorInterface $validator): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $relation = new Relation();
        $relation->setName('Related !');

        // Minimum validation for the relation attributes
        $errors = $validator->validate($relation);
        if (count($errors) > 0) {
            $errorsString = implode(',', (array) $errors);

            return new Response($errorsString, 400);
        }

        // tell Doctrine you want to (eventually) save the Relation (no queries yet)
        $entityManager->persist($relation);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new relation with id '.$relation->getId());
    }

    /**
     * @Route("/admin/relation/{id}", name="relation_show")
     *
     * Thanks to the ExtraBundle, no need to get a Repository and find the relation in the repository :)
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function show(Relation $relation): Response
    {
        return $this->render('relation/show.html.twig', ['item' => $relation]);
    }
}
