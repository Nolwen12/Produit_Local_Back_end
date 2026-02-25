<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;

final class ProduitController extends AbstractController
{
    #[Route('api/home', name: 'api_home', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categorie = $categorieRepository->createQueryBuilder('c')
            ->leftJoin('c.produits', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();

        return $this->json($categorie, 200, [], ['groups' => 'home:read']);
    }

    #[Route('/api/produit', name: 'api_produit', methods: ['GET'])]
    public function indexProduit(ProduitRepository $produitRepository): JsonResponse
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

    #[Route('api/categorie/{id}/produit', name:'api_produit_categorie', methods:['GET'])]
    public function indexProduitCategorie(Categorie $categorie): JsonResponse
    {
        $categories = $categorie->getProduits();
        return $this->json($categories, 200, [], ['groups' => 'produit_categorie:read']);
    }

    #[Route('api/recherche', name:'api_recherche', methods:['GET'])]
    public function recherche(ProduitRepository $produitRepository): JsonResponse
    {
        $criteria = $produitRepository->findAll();
        $produits = $produitRepository->search($criteria);
        return $this->json($produits, 200, [], ['groups' => 'produit:read']);
    }
}
