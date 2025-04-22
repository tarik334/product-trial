<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestWishlistController extends TestController
{
    #[Route('/test/wishlist/get/{token}')]
    public function get(string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'GET',
            '/wishlist',
        );
    }

    #[Route('/test/wishlist/add/{id}/{token}')]
    public function add(int $id, string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'POST',
            '/wishlist/' . $id,
        );
    }

    #[Route('/test/wishlist/delete/{id}/{token}')]
    public function delete(int $id, string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'DELETE',
            '/wishlist/' . $id,
        );
    }
}