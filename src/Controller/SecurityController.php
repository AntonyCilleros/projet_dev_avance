<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/api/connection', name: 'security_')]
class SecurityController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function createUser(
        Request                     $request,
        UsersRepository             $usersRepository,
        ValidatorInterface          $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface      $manager,
        Security                    $security
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des données requises
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            return $this->json(['message' => 'Données manquantes'], 400);
        }

        // Vérification de l'unicité du nom d'utilisateur
        if ($usersRepository->findOneBy(['username' => $data['username']])) {
            return $this->json(['message' => 'Ce nom d\'utilisateur existe déjà'], 400);
        }

        // Vérification de l'unicité de l'email
        if ($usersRepository->findOneBy(['email' => $data['email']])) {
            return $this->json(['message' => 'Cet email est déjà utilisé'], 400);
        }

        // Vérification de la sécurité du mot de passe
        if (strlen($data['password']) < 8) {
            return $this->json(['message' => 'Le mot de passe doit faire au moins 8 caractères'], 400);
        }
        if (!preg_match('/[A-Z]/', $data['password'])) {
            return $this->json(['message' => 'Le mot de passe doit contenir au moins une lettre majuscule'], 400);
        }
        if (!preg_match('/[a-z]/', $data['password'])) {
            return $this->json(['message' => 'Le mot de passe doit contenir au moins une lettre minuscule'], 400);
        }
        if (!preg_match('/[0-9]/', $data['password'])) {
            return $this->json(['message' => 'Le mot de passe doit contenir au moins un chiffre'], 400);
        }

        $user = new Users();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        // Hashage et définition du mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Validation des données
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = 'Erreur lors de la validation des données avec asserts : ' . $error->getMessage();
            }
            return $this->json(['messages' => $errorMessages], 400);
        }
        // on est bon !

        // Enregistrement de l'utilisateur
        $manager->persist($user);
        $manager->flush();

        // authenticate the user
        $security->login($user);

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('security_verify_email', $user,
            (new TemplatedEmail())
                ->from('dahyun@velvet-room.tech')
                ->to($user->getEmail())
                ->subject('Veuillez confirmer votre email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );


        return $this->json([
            'message' => 'Utilisateur créé avec succès',
        ], 201);
    }

    #[Route('/register_security_bundle', name: 'register_security_bundle')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('security_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('dahyun@velvet-room.tech', 'Dahyun'))
                    ->to((string)$user->getEmail())
                    ->subject('Veuillez confirmer votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify_email', name: 'verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var Users $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            dd($exception->getReason());
            $this->addFlash('verify_email_error', $exception->getReason());
            $this->addFlash('error', 'Une erreur est survenu pendant la vérification de votre email.');

            return $this->redirectToRoute('security_register');
        }

        $this->addFlash('success', 'Votre email a été vérifié.');

        return $this->redirectToRoute('app');
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(): JsonResponse {

        // Security-bundle gère la connexion
        return $this->json([
            'message' => 'Connexion réussie'
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return new JsonResponse(['message' => 'Déconnexion réussie'], 200);
    }

    #[Route('/get_user', name: 'connected', methods: ['GET'])]
    public function connected(): JsonResponse
    {
        $user = $this->getUser();
        return $user ? $this->json([
            'user' => [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'isVerified' => $user->isVerified()
            ]
        ]) : $this->json(['user' => null]);
    }
}
