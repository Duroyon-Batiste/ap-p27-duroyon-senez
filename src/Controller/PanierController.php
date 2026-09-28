<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function panier(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException(
                'Vous devez être connecté pour accéder au panier.'
            );
        }

        $client = $user->getClient();

        if (!$client instanceof Client) {
            throw $this->createNotFoundException(
                'Client introuvable.'
            );
        }

        $paniers = $client->getPaniers();

        $total = 0;

        foreach ($paniers as $panier) {
            $produit = $panier->getProduit();

            if ($produit !== null) {
                $total += $panier->getQte() * (float) $produit->getPrix();
            }
        }

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
            'total' => $total
        ]);
    }
}