<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    protected TokenStorageInterface $tokenInterface;
    private Security $security;

    public function __construct(TokenStorageInterface $tokenStorage, Security $security)
    {
        $this->tokenInterface = $tokenStorage;
        $this->security = $security;
    }

    /**
     * @Route("/admin/users", name="users")
     */
    public function index(UserRepository $UserRepository): Response
    {
        $users = $UserRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="user_show")
     *
     * Thanks to the ExtraBundle, no need to get a Repository and find the User in the repository :)
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * This route allows to get the current logged-in user profile. It simply redirects to the currently
     * authenticated user `users/{id}` route.
     *
     * @Route("/api/me", name="api_me")
     */
    public function me(): RedirectResponse
    {
        $user = $this->getUser();

        return $this->redirectToRoute('api_users_get_item', ['id' => $user->getId()]);
    }

//
//    /**
//     * This route allows to get the current logged-in user profile. It simply redirects to the currently
//     * authenticated user `users/{id}` route.
//     *
//     * @Route("/api/version", name="api_version")
//     */
//    public function version(LoggerInterface $logger): JsonResponse
//    {
//        $user = $this->getUser();
//
//        return new JsonResponse(
//            [
//                'code' => 200,
//                'messages' => [
//                    [
//                        'message' => 'Application message',
//                        'messageTemplate' => 'application.error',
//                    ],
//                ],
//            ],
//            200
//        );
//    }
}
