<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/create', name: 'create_user', methods: ['POST'])]
    public function createUser(
        Request $request,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        ObjectManager $manager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Vérification des données requises
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            return $this->json(['error' => 'Données manquantes'], 400);
        }

        // Vérification de l'unicité du nom d'utilisateur
        if ($userRepository->findOneBy(['username' => $data['username']])) {
            return $this->json(['error' => 'Ce nom d\'utilisateur existe déjà'], 400);
        }

        // Vérification de l'unicité de l'email
        if ($userRepository->findOneBy(['email' => $data['email']])) {
            return $this->json(['error' => 'Cet email est déjà utilisé'], 400);
        }

        // Vérification de la sécurité du mot de passe
        if (strlen($data['password']) < 8) {
            return $this->json(['error' => 'Le mot de passe doit faire au moins 8 caractères'], 400);
        }
        if (!preg_match('/[A-Z]/', $data['password'])) {
            return $this->json(['error' => 'Le mot de passe doit contenir au moins une lettre majuscule'], 400);
        }
        if (!preg_match('/[a-z]/', $data['password'])) {
            return $this->json(['error' => 'Le mot de passe doit contenir au moins une lettre minuscule'], 400);
        }
        if (!preg_match('/[0-9]/', $data['password'])) {
            return $this->json(['error' => 'Le mot de passe doit contenir au moins un chiffre'], 400);
        }

        $user = new User();
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
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        // Enregistrement de l'utilisateur
        $manager->persist($user);
        $manager->flush();

        return $this->json(['message' => 'Utilisateur créé avec succès'], 201);
    }
}
