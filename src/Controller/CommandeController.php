<?php

namespace App\Controller;

use App\Entity\CommandeProduit;
use App\Repository\CommandeProduitRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{
    #[Route('/api/commande', name: 'api_commande')]
    public function index(CommandeRepository $commandeRepository): JsonResponse
    {
        $commande = $commandeRepository->findAll();
        return $this->json($commande, 200, [], ['groups' => 'commande:read']);
    }

    #[Route('/api/commandeProduit', name: 'api_commandeProduit')]
    public function indexCommandeProduit(CommandeProduitRepository $commandeProduitRepository): JsonResponse
    {
        $commandeProduit = $commandeProduitRepository->findAll();
        return $this->json($commandeProduit, 200, [], ['groups' => 'commandeProduit:read']);
    }

    #[Route('/api/panier/ajouter', methods:['POST'])]
    public function addPanier(Request $request)
    {
       $data = json_decode($request->getContent(), true);

       return $this->json(['message' => 'Produit ajouté']);
    }
}
