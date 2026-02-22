<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;

final class ProduitController extends AbstractController
{
    #[Route('/api/produit', name: 'api_produit', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): JsonResponse
    {
        $produit = $produitRepository->findAll();
        return $this->json($produit, 200, [], ['groups' => 'produit:read']);
    }

    #[Route('api/categorie', name:'api_categorie', methods:['GET'])]
    public function indexCategorie(CategorieRepository $categorieRepository): JsonResponse
    {
        $categorie = $categorieRepository->findAll();
        return $this->json($categorie, 200, [], ['groups' => 'categorie:read']);
    }
}
