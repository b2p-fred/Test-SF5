<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

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
     * This route allows to get the current logged-in user profile. It simply redirects to the correct
     * `users/{id}` route.
     *
     * @Route("/api/me", name="api_me")
     *
     * @return JsonResponse
     */
    public function me(LoggerInterface $logger, SerializerInterface $serializer, UserRepository $userRepository): Response
    {
        $token = $this->tokenInterface->getToken();
        $user = $token->getUser();

        return $this->redirectToRoute('api_users_get_item', ['id' => $user->getId()]);
    }
}
