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
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            /*
             * DEBUG TEMPORAIRE
             *
             * Si le formulaire n'est pas valide,
             * Symfony affichera toutes les erreurs.
             */
            if (!$form->isValid()) {
                dd(
                    $form->getErrors(true, true)
                );
            }

            $data = $form->getData();

            /*
             * Vérification de l'adresse e-mail
             */
            $utilisateurExistant = $entityManager
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $data['email'],
                ]);

            if ($utilisateurExistant) {

                $form
                    ->get('email')
                    ->addError(
                        new FormError(
                            'Cette adresse e-mail est déjà utilisée.'
                        )
                    );

            } else {

                /*
                 * Création du User
                 */
                $user = new User();

                $user->setEmail(
                    $data['email']
                );

                $passwordHash = $passwordHasher->hashPassword(
                    $user,
                    $data['password']
                );

                $user->setPassword(
                    $passwordHash
                );


                /*
                 * Recherche du dernier code client
                 */
                $dernierCode = $entityManager
                    ->createQuery(
                        'SELECT MAX(c.code)
                         FROM App\Entity\Client c'
                    )
                    ->getSingleScalarResult();


                /*
                 * Génération du prochain numéro client
                 */
                if ($dernierCode) {

                    $numero = (int) substr(
                        $dernierCode,
                        3
                    );

                    $numero++;

                } else {

                    $numero = 1;
                }


                /*
                 * Exemple :
                 * CLI00000001
                 */
                $codeClient = 'CLI' . str_pad(
                    (string) $numero,
                    8,
                    '0',
                    STR_PAD_LEFT
                );


                /*
                 * Création du Client
                 */
                $client = new Client();

                $client->setCode(
                    $codeClient
                );

                $client->setNom(
                    $data['nom']
                );

                $client->setPrenom(
                    $data['prenom']
                );

                $client->setAdresse(
                    $data['adresse']
                );

                $client->setTelephone(
                    $data['telephone']
                );

                $client->setDateNaissance(
                    $data['dateNaissance']
                );


                /*
                 * Association Client -> User
                 */
                $client->setMail(
                    $user
                );


                /*
                 * Enregistrement en base
                 */
                $entityManager->persist(
                    $user
                );

                $entityManager->persist(
                    $client
                );

                $entityManager->flush();


                /*
                 * Message de confirmation
                 */
                $this->addFlash(
                    'success',
                    'Votre compte a été créé avec succès.'
                );


                /*
                 * Redirection vers la connexion
                 */
                return $this->redirectToRoute(
                    'app_login'
                );
            }
        }


        return $this->render(
            'registration/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}