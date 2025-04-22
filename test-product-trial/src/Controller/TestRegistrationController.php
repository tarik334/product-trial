<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestRegistrationController extends TestController
{
    #[Route('/test/account/{username}/{password}/{firstname}/{email}')]
    public function account(string $username, string $password, string $firstname, string $email): Response
    {
        $user = [
            'username' => $username,
            'password' => $password,
            'firstname' => $firstname,
            'email' => $email,
        ];

        return $this->getResponse(
            'POST',
            '/account',
            ['json' => $user],
        );
    }

    #[Route('/test/token/{email}/{password}')]
    public function token(string $email, string $password): Response
    {
        $user = [
            'username' => $email,
            'password' => $password,
        ];

        return $this->getResponse(
            'POST',
            '/token',
            ['json' => $user],
        );
    }
}