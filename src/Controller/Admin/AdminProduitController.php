<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/produit')]
final class AdminProduitController extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->findAll();
        return $this->json($produit, 200, [], ['groups' => 'adminProduit:read']);
    }

    #[Route('/new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->json($produit, Response::HTTP_CREATED,[],['groups' => 'produit:update']);
        }

        return $this->json([$produit, $form], 200, [], ['groups' => 'adminProduit:update']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->json($produit, 200, [], ['groups' => 'adminProduit:read']);
    }

    #[Route('/{id}/edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->json($produit, Response::HTTP_OK, [], ['groups' => 'adminProduit:update']);
        }

        return $this->json([$produit, $form], 200, [], ['groups' => 'adminProduit:update']);
    }

    #[Route('/{id}', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
