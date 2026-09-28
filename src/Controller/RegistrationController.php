<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Création du formulaire
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        // Vérification de l'envoi et de la validité du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Vérification si l'adresse e-mail existe déjà
            $utilisateurExistant = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['email' => $data['email']]);

            if ($utilisateurExistant) {
                // Affichage d'une erreur si l'e-mail est déjà utilisé
                $form->get('email')->addError(
                    new FormError(
                        'Cette adresse e-mail est déjà utilisée.'
                    )
                );
            } else {
                // Création du compte utilisateur
                $user = new User();
                $user->setEmail($data['email']);
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $data['password']
                    )
                );

                // Génération du code client : CLI + 8 chiffres
                $dernierCode = $entityManager->createQuery(
                    'SELECT MAX(c.code) FROM App\Entity\Client c'
                )->getSingleScalarResult();

                if ($dernierCode) {
                    $numero = (int) substr($dernierCode, 3) + 1;
                } else {
                    $numero = 1;
                }

                $codeClient = 'CLI' . str_pad(
                    (string) $numero,
                    8,
                    '0',
                    STR_PAD_LEFT
                );

                // Création de la fiche client
                $client = new Client();
                $client->setCode($codeClient);
                $client->setNom($data['nom']);
                $client->setPrenom($data['prenom']);
                $client->setAdresse($data['adresse']);
                $client->setTelephone($data['telephone']);
                $client->setDateNaissance($data['dateNaissance']);

                // Association du compte utilisateur et du client
                $client->setMail($user);

                // Enregistrement en base de données
                $entityManager->persist($user);
                $entityManager->persist($client);
                $entityManager->flush();

                // Message de confirmation
                $this->addFlash(
                    'success',
                    'Inscription réussie !'
                );

                return $this->redirectToRoute('app_registration');
            }
        }

        // Affichage du formulaire
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}