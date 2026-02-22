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

    #[Route('api/adresse', name: 'api_adresse', methods:['GET'])]
    public function indexAdresse(AdresseRepository $adresseRepository): JsonResponse
    {
        $adresse = $adresseRepository->findAll();
        return $this->json($adresse, 200, [], ['groups' => 'adresse:read']);
    }    
}
