<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/user')]
final class AdminUserController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    #[Route(methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->json($user, 200, [], ['groups' => 'adminUser:read']);
    }

    #[Route('/new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);

            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json($user, Response::HTTP_CREATED,[],['groups' => 'adminUser:update']);
        }

        return $this->json([$user, $form], 200, [], ['groups' => 'adminUser:update']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->json($user, 200, [], ['groups' => 'adminUser:read']);
    }

    #[Route('/{id}/edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->json($user, Response::HTTP_OK, [], ['groups' => 'adminUser:update']);
        }

         return $this->json([$user, $form], 200, [], ['groups' => 'adminUser:update']);
    }

    #[Route('/{id}', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
