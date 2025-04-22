<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestBasketController extends TestController
{
    #[Route('/test/basket/get/{token}')]
    public function get(string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'GET',
            '/basket',
        );
    }

    #[Route('/test/basket/add/{id}/{quantity}/{token}')]
    public function add(int $id, int $quantity, string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'POST',
            '/basket/' . $id . '/' . $quantity,
        );
    }

    #[Route('/test/basket/delete/{id}/{quantity}/{token}')]
    public function delete(int $id, int $quantity, string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'DELETE',
            '/basket/' . $id . '/' . $quantity,
        );
    }

    #[Route('/test/basket/delete/{token}')]
    public function free(string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'DELETE',
            '/basket',
        );
    }
}