<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/categorie')]
final class CategorieController extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categorie = $categorieRepository->findAll();
        return $this->json($categorie, 200, [], ['groups' => 'adminCat:read']);
    }

    #[Route('/new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->json($categorie,  Response::HTTP_CREATED, [], ['groups' => 'adminCat:update']);
        }

        return $this->json([$categorie, $form], 200, [], ['groups' => 'adminCat:update']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->json($categorie, 200, [], ['groups' => 'adminCat:read']);
    }

    #[Route('/{id}/edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->json($categorie, Response::HTTP_OK, [], ['groups' => 'adminCat:update']);
        }

         return $this->json([$categorie, $form], 200, [], ['groups' => 'adminCat:update']);
    }

    #[Route('/{id}', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
