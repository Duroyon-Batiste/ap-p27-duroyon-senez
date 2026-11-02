<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Panier;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Entity\HistoriqueEtat;
use App\Entity\Etat;
use App\Entity\Magasin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function panier(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $client = $user->getClient();

        if (!$client instanceof Client) {
            throw $this->createNotFoundException('Client introuvable.');
        }

        $paniers = $client->getPaniers();

        $total = 0;

        foreach ($paniers as $panier) {
            $produit = $panier->getProduit();

            if ($produit !== null) {
                $total += $panier->getQte() * (float) $produit->getPrix();
            }
        }

        $magasins = $entityManager
            ->getRepository(Magasin::class)
            ->findBy([], ['ville' => 'ASC']);

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
            'total' => $total,
            'magasins' => $magasins,
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
            throw $this->createNotFoundException('Client introuvable.');
        }

        $panier = $entityManager
            ->getRepository(Panier::class)
            ->find($id);

        if (!$panier instanceof Panier) {
            throw $this->createNotFoundException('Panier introuvable.');
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
            throw $this->createNotFoundException('Client introuvable.');
        }

        $panier = $entityManager
            ->getRepository(Panier::class)
            ->find($id);

        if (!$panier instanceof Panier) {
            throw $this->createNotFoundException('Panier introuvable.');
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

    #[Route('/panier/commander', name: 'app_panier_commander', methods: ['POST'])]
    public function commander(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $client = $user->getClient();

        if (!$client instanceof Client) {
            throw $this->createNotFoundException('Client introuvable.');
        }

        // Vérification du formulaire
        if (!$this->isCsrfTokenValid(
            'commander',
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException(
                'Formulaire invalide.'
            );
        }

        $paniers = $client->getPaniers();

        if ($paniers->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');

            return $this->redirectToRoute('app_panier');
        }

        // Récupération du mode de livraison
        $modeLivraison = $request->request->get('modeLivraison');

        if (!in_array($modeLivraison, ['domicile', 'magasin'], true)) {
            $this->addFlash('error', 'Mode de livraison invalide.');

            return $this->redirectToRoute('app_panier');
        }

        $adresseLivraison = null;
        $magasin = null;
        $fraisLivraison = '0.00';

        if ($modeLivraison === 'domicile') {
            $adresseLivraison = trim(
                (string) $request->request->get('adresseLivraison')
            );

            if ($adresseLivraison === '') {
                $this->addFlash(
                    'error',
                    'Veuillez saisir une adresse de livraison.'
                );

                return $this->redirectToRoute('app_panier');
            }

            $fraisLivraison = '10.00';
        }

        if ($modeLivraison === 'magasin') {
            $magasinId = $request->request->get('magasin');

            $magasin = $entityManager
                ->getRepository(Magasin::class)
                ->find($magasinId);

            if (!$magasin instanceof Magasin) {
                $this->addFlash(
                    'error',
                    'Veuillez sélectionner un magasin valide.'
                );

                return $this->redirectToRoute('app_panier');
            }
        }

        // Récupération de l'état initial
        $etatInitial = $entityManager
            ->getRepository(Etat::class)
            ->findOneBy(['nom' => 'transmise']);

        if (!$etatInitial instanceof Etat) {
            throw $this->createNotFoundException(
                'L’état "transmise" est introuvable dans la base de données.'
            );
        }

        // Création de la commande
        $commande = new Commande();

        $commande->setDate(new \DateTime());
        $commande->setClient($client);
        $commande->setModeLivraison($modeLivraison);
        $commande->setAdresseLivraison($adresseLivraison);
        $commande->setMagasin($magasin);
        $commande->setFraisLivraison($fraisLivraison);

        $entityManager->persist($commande);

        // Copie des produits du panier dans la commande
        foreach ($paniers as $panier) {
            $produit = $panier->getProduit();

            if ($produit === null) {
                continue;
            }

            $produitCommande = new ProduitCommande();

            $produitCommande->setProduit($produit);
            $produitCommande->setQte($panier->getQte());
            $produitCommande->setCommande($commande);

            $commande->addProduitCommande($produitCommande);

            $entityManager->persist($produitCommande);
        }

        // Création du premier état de la commande
        $historiqueEtat = new HistoriqueEtat();

        $historiqueEtat->setCommande($commande);
        $historiqueEtat->setEtat($etatInitial);
        $historiqueEtat->setDate(new \DateTime());

        $commande->addHistoriqueEtat($historiqueEtat);

        $entityManager->persist($historiqueEtat);

        // Suppression des articles du panier
        foreach ($paniers->toArray() as $panier) {
            $entityManager->remove($panier);
        }

        // Enregistrement de la commande
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Votre commande a bien été enregistrée !'
        );

        return $this->redirectToRoute('app_suivi_commandes');
    }
}
