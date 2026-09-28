<?php

namespace App\Controller;

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
}