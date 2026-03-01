<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route(path: 'api/login', name: 'app_login', methods: ['POST'])]
    public function login(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher, JwtService $jwtService): JsonResponse
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        $data = json_decode($request->getContent(), true);

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $token = $jwtService->generate([
            'id' => $user->getId(),
            'roles' => $user->getRoles(),
        ]);

        return new JsonResponse(['token' => $token]);
    }

    #[Route(path: 'api/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
