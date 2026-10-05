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

    #[Route('/panier/{id}/add', name: 'app_panier_add', methods: ['POST'])]
    public function add(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $client = $user->getClient();

        if (!$client instanceof Client) {
            throw $this->createNotFoundException(
                'Client introuvable.'
            );
        }

        $panier = $entityManager
            ->getRepository(Panier::class)
            ->find($id);

        if (!$panier instanceof Panier) {
            throw $this->createNotFoundException(
                'Panier introuvable.'
            );
        }

        if ($panier->getClient() !== $client) {
            throw $this->createAccessDeniedException(
                'Ce panier ne vous appartient pas.'
            );
        }

        $panier->setQte($panier->getQte() + 1);

        $entityManager->flush();

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/{id}/remove', name: 'app_panier_remove', methods: ['POST'])]
    public function remove(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $client = $user->getClient();

        if (!$client instanceof Client) {
            throw $this->createNotFoundException(
                'Client introuvable.'
            );
        }

        $panier = $entityManager
            ->getRepository(Panier::class)
            ->find($id);

        if (!$panier instanceof Panier) {
            throw $this->createNotFoundException(
                'Panier introuvable.'
            );
        }

        if ($panier->getClient() !== $client) {
            throw $this->createAccessDeniedException(
                'Ce panier ne vous appartient pas.'
            );
        }

        if ($panier->getQte() > 1) {
            $panier->setQte($panier->getQte() - 1);
        } else {
            $entityManager->remove($panier);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_panier');
    }
}