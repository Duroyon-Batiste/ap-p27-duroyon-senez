<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_produit')]
    public function produit(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $produit = $entityManager
            ->getRepository(Produit::class)
            ->find($id);

        if (!$produit instanceof Produit) {
            throw $this->createNotFoundException(
                'Produit introuvable.'
            );
        }

        return $this->render('produit/index.html.twig', [
            'produit' => $produit
        ]);
    }

    #[Route('/produit/{id}/add-to-cart', name: 'app_produit_add_to_cart', methods: ['POST'])]
    public function addToCart(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $client = $user->getClient();

        if (!$client) {
            throw $this->createNotFoundException(
                'Client introuvable.'
            );
        }

        $produit = $entityManager
            ->getRepository(Produit::class)
            ->find($id);

        if (!$produit instanceof Produit) {
            throw $this->createNotFoundException(
                'Produit introuvable.'
            );
        }

        $panier = null;

        foreach ($client->getPaniers() as $panierClient) {
            if ($panierClient->getProduit() === $produit) {
                $panier = $panierClient;
                break;
            }
        }

        if ($panier) {
            $panier->setQte($panier->getQte() + 1);
        } else {
            $panier = new Panier();

            $panier->setClient($client);
            $panier->setProduit($produit);
            $panier->setQte(1);

            $entityManager->persist($panier);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_panier');
    }
}