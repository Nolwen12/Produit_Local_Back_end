<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/adresse')]
final class AdresseController extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function index(AdresseRepository $adresseRepository): Response
    {
        $adresse = $adresseRepository->findAll();
        return $this->json($adresse, 200, [], ['groups' => 'adresse:read']);
    }

    #[Route('/new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->json($adresse,  Response::HTTP_CREATED, [], ['groups' => 'adresse:update']);
        }

        return $this->json([$adresse, $form], 200, [], ['groups' => 'adresse:update']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        return $this->json($adresse, 200, [], ['groups' => 'adresse:read']);
    }

    #[Route('/{id}/edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->json($adresse, Response::HTTP_OK, [], ['groups' => 'adresse:update']);
        }

        return $this->json([$adresse, $form], 200, [], ['groups' => 'adresse:update']);
    }

    #[Route('/{id}', methods: ['POST'])]
    public function delete(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adresse);
            $entityManager->flush();
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
