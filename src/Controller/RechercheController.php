<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\SportRepository;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche', methods: ['GET'])]
    public function index(
        Request $request,
        ProduitRepository $produitRepository,
        SportRepository $sportRepository,
        FournisseurRepository $fournisseurRepository
    ): Response {
        // Récupération des critères de recherche
        $recherche = trim((string) $request->query->get('q', ''));

        $sportId = (string) $request->query->get('sport', '');
        $fournisseurId = (string) $request->query->get('fournisseur', '');

        $marque = (string) $request->query->get('marque', '');
        $couleur = (string) $request->query->get('couleur', '');
        $taille = (string) $request->query->get('taille', '');
        $style = (string) $request->query->get('style', '');

        $prixMin = (string) $request->query->get('prixMin', '');
        $prixMax = (string) $request->query->get('prixMax', '');

        // Création de la requête de recherche
        $queryBuilder = $produitRepository->createQueryBuilder('p')
            ->leftJoin('p.sport', 's')
            ->addSelect('s')
            ->leftJoin('p.fournisseur', 'f')
            ->addSelect('f');

        // Recherche par nom (description) ou référence
        if ($recherche !== '') {
            $queryBuilder
                ->andWhere(
                    'LOWER(p.description) LIKE :recherche
                    OR LOWER(p.ref) LIKE :recherche'
                )
                ->setParameter('recherche', '%' . mb_strtolower($recherche) . '%');
        }

        // Filtre par sport
        if (ctype_digit($sportId) && $sportId !== '') {
            $queryBuilder
                ->andWhere('IDENTITY(p.sport) = :sportId')
                ->setParameter('sportId', (int) $sportId);
        }

        // Filtre par fournisseur
        if (ctype_digit($fournisseurId) && $fournisseurId !== '') {
            $queryBuilder
                ->andWhere('IDENTITY(p.fournisseur) = :fournisseurId')
                ->setParameter('fournisseurId', (int) $fournisseurId);
        }

        // Filtre par marque
        if ($marque !== '') {
            $queryBuilder
                ->andWhere('p.marque = :marque')
                ->setParameter('marque', $marque);
        }

        // Filtre par couleur
        if ($couleur !== '') {
            $queryBuilder
                ->andWhere('p.couleur = :couleur')
                ->setParameter('couleur', $couleur);
        }

        // Filtre par taille
        if ($taille !== '') {
            $queryBuilder
                ->andWhere('p.taille = :taille')
                ->setParameter('taille', $taille);
        }

        // Filtre par style
        if ($style !== '') {
            $queryBuilder
                ->andWhere('p.style = :style')
                ->setParameter('style', $style);
        }

        // Filtre par prix minimum
        if ($prixMin !== '' && is_numeric($prixMin) && (float) $prixMin >= 0) {
            $queryBuilder
                ->andWhere('p.prix >= :prixMin')
                ->setParameter('prixMin', $prixMin);
        }

        // Filtre par prix maximum
        if ($prixMax !== '' && is_numeric($prixMax) && (float) $prixMax >= 0) {
            $queryBuilder
                ->andWhere('p.prix <= :prixMax')
                ->setParameter('prixMax', $prixMax);
        }

        // Tri alphabétique des résultats
        $queryBuilder->orderBy('p.description', 'ASC');

        // Exécution de la recherche
        $produits = $queryBuilder->getQuery()->getResult();

        /*
         * Récupération des valeurs disponibles
         * pour alimenter les listes déroulantes.
         */
        $tousLesProduits = $produitRepository->findAll();

        $marques = $this->getValeursUniques(
            $tousLesProduits,
            fn (Produit $produit) => $produit->getMarque()
        );

        $couleurs = $this->getValeursUniques(
            $tousLesProduits,
            fn (Produit $produit) => $produit->getCouleur()
        );

        $tailles = $this->getValeursUniques(
            $tousLesProduits,
            fn (Produit $produit) => $produit->getTaille()
        );

        $styles = $this->getValeursUniques(
            $tousLesProduits,
            fn (Produit $produit) => $produit->getStyle()
        );

        return $this->render('recherche/index.html.twig', [
            'produits' => $produits,

            'sports' => $sportRepository->findBy([], ['nom' => 'ASC']),
            'fournisseurs' => $fournisseurRepository->findBy([], ['nom' => 'ASC']),

            'marques' => $marques,
            'couleurs' => $couleurs,
            'tailles' => $tailles,
            'styles' => $styles,

            // Conservation des critères dans le formulaire
            'criteres' => [
                'q' => $recherche,
                'sport' => $sportId,
                'fournisseur' => $fournisseurId,
                'marque' => $marque,
                'couleur' => $couleur,
                'taille' => $taille,
                'style' => $style,
                'prixMin' => $prixMin,
                'prixMax' => $prixMax,
            ],
        ]);
    }

    /**
     * Récupère les valeurs distinctes d'une caractéristique des produits.
     */
    private function getValeursUniques(array $produits, callable $getter): array
    {
        $valeurs = [];

        foreach ($produits as $produit) {
            $valeur = $getter($produit);

            if ($valeur !== null && $valeur !== '') {
                $valeurs[] = $valeur;
            }
        }

        $valeurs = array_unique($valeurs);
        sort($valeurs);

        return array_values($valeurs);
    }
}
