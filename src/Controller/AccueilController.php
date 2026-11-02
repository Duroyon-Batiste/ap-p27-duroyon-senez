<?php

namespace App\Controller;

use App\Entity\Sport;
use App\Repository\ProduitRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(
        ProduitRepository $produitRepository,
        SportRepository $sportRepository
    ): Response {
        // Sports que l'on souhaite mettre en avant sur l'accueil
        $sportsMisEnAvant = [
            'Football',
            'Running',
            'Fitness',
            'Basketball'
        ];

        $sports = $sportRepository->createQueryBuilder('s')
            ->leftJoin('s.produits', 'p')
            ->addSelect('p')
            ->where('s.nom IN (:sports)')
            ->andWhere('p.id IS NOT NULL')
            ->setParameter('sports', $sportsMisEnAvant)
            ->getQuery()
            ->getResult();

        // Produits affichés dans les rayons
        $produits = $produitRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'produits' => $produits,
            'sports' => $sports
        ]);
    }
}
