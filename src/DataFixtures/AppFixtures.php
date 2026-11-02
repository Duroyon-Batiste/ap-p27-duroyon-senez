<?php

namespace App\DataFixtures;

use App\Entity\Sport;
use App\Entity\Fournisseur;
use App\Entity\Zone;
use App\Entity\Magasin;
use App\Entity\Entrepot;
use App\Entity\Produit;
use App\Entity\Photo;
use App\Entity\StockMagasin;
use App\Entity\StockEntrepot;
use App\Entity\Etat;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /*
         * SPORTS
         */
        $sportsNoms = [
            'Football',
            'Basketball',
            'Running',
            'Fitness',
            'Tennis',
            'Natation',
            'Cyclisme',
        ];

        $sports = [];

        foreach ($sportsNoms as $nom) {
            $sport = new Sport();
            $sport->setNom($nom);

            $manager->persist($sport);
            $sports[$nom] = $sport;
        }

        /*
         * FOURNISSEURS
         */
        $fournisseursNoms = [
            'Sport Distribution',
            'Athletic Supply',
            'Pro Sport France',
            'Global Sports',
            'Performance Shop',
            'Active Equipment',
            'Sport Factory',
            'Premium Sports',
        ];

        $fournisseurs = [];

        foreach ($fournisseursNoms as $nom) {
            $fournisseur = new Fournisseur();
            $fournisseur->setNom($nom);

            $manager->persist($fournisseur);
            $fournisseurs[] = $fournisseur;
        }

        /*
         * ZONES
         */
        $zonesNoms = [
            'Nord',
            'Sud',
            'Est',
            'Ouest',
            'Centre',
        ];

        $zones = [];

        foreach ($zonesNoms as $nom) {
            $zone = new Zone();
            $zone->setNom($nom);

            $manager->persist($zone);
            $zones[] = $zone;
        }

        /*
         * MAGASINS
         */
        $villesMagasins = [
            'Lille',
            'Valenciennes',
            'Douai',
            'Paris',
            'Lyon',
            'Marseille',
            'Bordeaux',
            'Nantes',
            'Toulouse',
            'Strasbourg',
        ];

        $magasins = [];

        foreach ($villesMagasins as $index => $ville) {
            $magasin = new Magasin();
            $magasin->setVille($ville);
            $magasin->setZone($zones[$index % count($zones)]);

            $manager->persist($magasin);
            $magasins[] = $magasin;
        }

        /*
         * ENTREPOTS
         */
        $villesEntrepots = [
            'Lille',
            'Paris',
            'Lyon',
            'Bordeaux',
            'Nantes',
        ];

        $entrepots = [];

        foreach ($villesEntrepots as $index => $ville) {
            $entrepot = new Entrepot();
            $entrepot->setVille($ville);
            $entrepot->setZone($zones[$index % count($zones)]);

            $manager->persist($entrepot);
            $entrepots[] = $entrepot;
        }

        /*
         * ETATS DE COMMANDE
         *
         * Aucun historique ni aucune commande ne sont créés.
         */
        $etats = [
            [
                'nom' => 'transmise',
                'description' => 'Commande transmise au service client, en attente de validation.',
            ],
            [
                'nom' => 'validée',
                'description' => 'Commande confirmée par le service client, en attente de préparation.',
            ],
            [
                'nom' => 'en préparation',
                'description' => 'Commande en cours de préparation.',
            ],
            [
                'nom' => 'expédiée',
                'description' => 'Commande expédiée au client.',
            ],
            [
                'nom' => 'livrée',
                'description' => 'Commande réceptionnée par le client.',
            ],
            [
                'nom' => 'retirée',
                'description' => 'Commande récupérée par le client en magasin.',
            ],
        ];

        foreach ($etats as $donnees) {
            $etat = new Etat();
            $etat->setNom($donnees['nom']);
            $etat->setDescription($donnees['description']);

            $manager->persist($etat);
        }

        /*
         * CATALOGUE DE PRODUITS
         *
         * Chaque sport possède plusieurs types de produits.
         * Le nombre de produits est défini dans la propriété "quantite".
         */
        $catalogue = [
            'Football' => [
                'code' => 'FOO',
                'quantite' => 20,
                'produits' => [
                    'Ballon de football',
                    'Chaussures de football',
                    'Maillot de football',
                    'Short de football',
                    'Protège-tibias',
                    'Gants de gardien',
                    'Chaussettes de football',
                    'Sac de sport',
                    'Veste de football',
                    'Pantalon de survêtement',
                ],
            ],

            'Basketball' => [
                'code' => 'BAS',
                'quantite' => 15,
                'produits' => [
                    'Ballon de basketball',
                    'Chaussures de basketball',
                    'Maillot de basketball',
                    'Short de basketball',
                    'Manchons de compression',
                    'Chaussettes de sport',
                    'Sac à ballon',
                    'Débardeur de basketball',
                ],
            ],

            'Running' => [
                'code' => 'RUN',
                'quantite' => 15,
                'produits' => [
                    'Chaussures de running',
                    'T-shirt de running',
                    'Short de running',
                    'Veste de running',
                    'Legging de running',
                    'Gourde de sport',
                    'Ceinture de running',
                    'Casquette de running',
                ],
            ],

            'Fitness' => [
                'code' => 'FIT',
                'quantite' => 15,
                'produits' => [
                    'Tapis de fitness',
                    'Haltères',
                    'Bande élastique',
                    'Gants de musculation',
                    'T-shirt de fitness',
                    'Legging de fitness',
                    'Short de fitness',
                    'Corde à sauter',
                ],
            ],

            'Tennis' => [
                'code' => 'TEN',
                'quantite' => 10,
                'produits' => [
                    'Raquette de tennis',
                    'Balles de tennis',
                    'Chaussures de tennis',
                    'Polo de tennis',
                    'Short de tennis',
                    'Sac de tennis',
                    'Grip de raquette',
                ],
            ],

            'Natation' => [
                'code' => 'NAT',
                'quantite' => 10,
                'produits' => [
                    'Maillot de bain',
                    'Lunettes de natation',
                    'Bonnet de bain',
                    'Palmes de natation',
                    'Planche de natation',
                    'Serviette de piscine',
                    'Sac de piscine',
                ],
            ],

            'Cyclisme' => [
                'code' => 'CYC',
                'quantite' => 15,
                'produits' => [
                    'Vélo de route',
                    'Casque de cyclisme',
                    'Maillot de cyclisme',
                    'Cuissard de cyclisme',
                    'Gants de cyclisme',
                    'Lunettes de cyclisme',
                    'Gourde de vélo',
                    'Éclairage de vélo',
                ],
            ],
        ];

        /*
         * VALEURS UTILISEES POUR VARIER LES PRODUITS
         */
        $marques = [
            'Nike',
            'Adidas',
            'Puma',
            'Asics',
            'Under Armour',
            'Reebok',
            'New Balance',
            'Decathlon',
        ];

        $couleurs = [
            'Noir',
            'Blanc',
            'Rouge',
            'Bleu',
            'Vert',
            'Gris',
            'Orange',
            'Jaune',
            'Violet',
            'Rose',
        ];

        $tailles = [
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
            'Unique',
        ];

        $styles = [
            'homme',
            'femme',
            'enfants',
            'mixte',
        ];

        $prixPossibles = [
            '9.99',
            '14.99',
            '19.99',
            '24.99',
            '29.99',
            '34.99',
            '39.99',
            '44.99',
            '49.99',
            '59.99',
            '69.99',
            '79.99',
            '89.99',
            '99.99',
            '119.99',
            '149.99',
            '199.99',
            '249.99',
        ];

        /*
         * CREATION DES 100 PRODUITS
         */
        $produits = [];
        $numeroGlobal = 1;

        foreach ($catalogue as $nomSport => $donneesSport) {
            for ($i = 1; $i <= $donneesSport['quantite']; $i++) {
                $libelles = $donneesSport['produits'];
                $libelle = $libelles[($i - 1) % count($libelles)];

                $produit = new Produit();

                $produit->setRef(sprintf(
                    '%s-%03d',
                    $donneesSport['code'],
                    $i
                ));

                $produit->setDescription(
                    $libelle . ' - modèle sport numéro ' . $i
                );

                $produit->setPrix(
                    $prixPossibles[($numeroGlobal - 1) % count($prixPossibles)]
                );

                $produit->setMarque(
                    $marques[($numeroGlobal - 1) % count($marques)]
                );

                $produit->setCouleur(
                    $couleurs[($numeroGlobal - 1) % count($couleurs)]
                );

                $produit->setTaille(
                    $tailles[($numeroGlobal - 1) % count($tailles)]
                );

                $produit->setStyle(
                    $styles[($numeroGlobal - 1) % count($styles)]
                );

                $produit->setSport($sports[$nomSport]);

                $produit->setFournisseur(
                    $fournisseurs[($numeroGlobal - 1) % count($fournisseurs)]
                );

                $manager->persist($produit);

                /*
                 * PHOTO
                 *
                 * Les fichiers images doivent être placés dans
                 * public/images/produits/.
                 */
                $photo = new Photo();
                $photo->setChemin('images/produits/placeholder.jpg');
                $photo->setProduit($produit);

                $manager->persist($photo);

                $produits[] = $produit;
                $numeroGlobal++;
            }
        }

        /*
         * STOCKS DES MAGASINS ET DES ENTREPOTS
         *
         * Chaque produit est présent dans deux magasins
         * et dans un entrepôt.
         */
        foreach ($produits as $index => $produit) {
            // Premier magasin
            $stockMagasin1 = new StockMagasin();
            $stockMagasin1->setProduit($produit);
            $stockMagasin1->setMagasin(
                $magasins[$index % count($magasins)]
            );
            $stockMagasin1->setQte(5 + ($index % 26));

            $manager->persist($stockMagasin1);

            // Deuxième magasin
            $stockMagasin2 = new StockMagasin();
            $stockMagasin2->setProduit($produit);
            $stockMagasin2->setMagasin(
                $magasins[($index + 3) % count($magasins)]
            );
            $stockMagasin2->setQte(3 + (($index * 2) % 20));

            $manager->persist($stockMagasin2);

            // Entrepôt
            $stockEntrepot = new StockEntrepot();
            $stockEntrepot->setProduit($produit);
            $stockEntrepot->setEntrepot(
                $entrepots[$index % count($entrepots)]
            );
            $stockEntrepot->setQte(20 + (($index * 3) % 81));

            $manager->persist($stockEntrepot);
        }

        /*
         * ENREGISTREMENT
         */
        $manager->flush();
    }
}
