<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class RegistrationController extends AbstractController
{
    #[Route('/account', methods: ['POST'], format: 'json')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
    ): Response
    {
        $user = new User();

        $payload = $request->getPayload();

        if ($payload->has('username')) {
            $user->setUsername($payload->get('username'));
        }

        if ($payload->has('password')) {
            $user->setPassword($userPasswordHasher->hashPassword($user, $payload->get('password')));
        }

        if ($payload->has('firstname')) {
            $user->setFirstname($payload->get('firstname'));
        }

        if ($payload->has('email')) {
            $user->setEmail($payload->get('email'));
        }

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'ok',
            'result' => ['id' => $user->getId()],
        ]);
    }
}
