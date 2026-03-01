<?php

namespace App\Controller;

use App\Repository\AdresseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('api/user', name: 'api_user', methods:['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findAll();
        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }   
}
