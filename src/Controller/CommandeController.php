<?php


namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/mes-commandes', name: 'app_suivi_commandes')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $client = $user->getClient();

        if (!$client instanceof Client) {
            throw $this->createNotFoundException(
                'Aucun profil client associé à ce compte.'
            );
        }

        $commandesEnCours = [];
        $commandesPassees = [];

        foreach ($client->getCommandes() as $commande) {
            if (!$commande instanceof Commande) {
                continue;
            }

            // Trie les états du plus récent au plus ancien.
            $historiqueEtats = $commande->getHistoriqueEtats()->toArray();

            usort(
                $historiqueEtats,
                static function ($a, $b): int {
                    return ($b->getDate()?->getTimestamp() ?? 0)
                        <=> ($a->getDate()?->getTimestamp() ?? 0);
                }
            );

            // Le premier état est le plus récent.
            $dernierEtat = $historiqueEtats[0] ?? null;

            $nomEtat = mb_strtolower(
                trim($dernierEtat?->getEtat()?->getNom() ?? '')
            );

            // États considérés comme terminaux.
            $etatsFinaux = [
                'livrée',
                'livree',
                'livré',
                'livre',
                'terminée',
                'terminee',
                'terminé',
                'termine',
                'annulée',
                'annulee',
                'annulé',
                'annule',
            ];

            // Calcul du montant total de la commande.
            $total = 0.0;

            foreach ($commande->getProduitCommandes() as $produitCommande) {
                $produit = $produitCommande->getProduit();

                if ($produit !== null) {
                    $total += $produitCommande->getQte()
                        * (float) $produit->getPrix();
                }
            }

            // Les données préparées sont transmises au template.
            $donneesCommande = [
                'commande' => $commande,
                'historiqueEtats' => $historiqueEtats,
                'dernierEtat' => $dernierEtat,
                'total' => $total,
            ];

            if (in_array($nomEtat, $etatsFinaux, true)) {
                $commandesPassees[] = $donneesCommande;
            } else {
                $commandesEnCours[] = $donneesCommande;
            }
        }

        // Affiche les commandes les plus récentes en premier.
        $trierParDate = static function (array &$commandes): void {
            usort(
                $commandes,
                static function (array $a, array $b): int {
                    return ($b['commande']->getDate()?->getTimestamp() ?? 0)
                        <=> ($a['commande']->getDate()?->getTimestamp() ?? 0);
                }
            );
        };

        $trierParDate($commandesEnCours);
        $trierParDate($commandesPassees);

        return $this->render('commande/index.html.twig', [
            'commandesEnCours' => $commandesEnCours,
            'commandesPassees' => $commandesPassees,
        ]);
    }
}
