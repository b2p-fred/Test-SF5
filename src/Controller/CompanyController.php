<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     */
    public function index(): Response
    {
        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }

    /**
     * @Route("/company/create", name="create_company")
     */
    public function createCompany(ValidatorInterface $validator): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $company = new Company();
        $company->setName('Company !');

        // Minimum validation for the company attributes
        $errors = $validator->validate($company);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        // tell Doctrine you want to (eventually) save the Company (no queries yet)
        $entityManager->persist($company);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new company with id '.$company->getId());
    }

    /**
     * @Route("/company/{id}", name="company_show")
     *
     * Thanks to the ExtraBundle, no need to get a Repository and find the company in the repository -)
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function show(Company $company): Response
    {
        return new Response('Check out this great company: '.$company->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('company/show.html.twig', ['company' => $company]);
    }
}
