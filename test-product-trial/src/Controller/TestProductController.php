<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestProductController extends TestController
{
    #[Route('/test/products/add/{name}/{description}/{token}')]
    public function add(string $name, string $description, string $token): Response
    {
        $product = [
            'code' => 'code',
            'name' => $name,
            'description' => $description,
            'image' => 'image',
            'category' => 'category',
            'price' => '1.0',
            'quantity' => 2,
            'internalReference' => 'internalReference',
            'shellId' => 3,
            'inventoryStatus' => 'INSTOCK',
            'rating' => '4.0',
        ];

        $this->accessToken = $token;

        return $this->getResponse(
            'POST',
            '/products',
            ['json' => $product],
        );
    }

    #[Route('/test/products/list/{token}')]
    public function list(string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'GET',
            '/products',
        );
    }

    #[Route('/test/products/get/{id}/{token}')]
    public function get(int $id, string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'GET',
            '/product/' . $id,
        );
    }

    #[Route('/test/products/update/{id}/{name}/{description}/{token}')]
    public function update(int $id, string $name, string $description, string $token): Response
    {
        $product = [
            'name' => $name,
            'description' => $description,
        ];

        $this->accessToken = $token;

        return $this->getResponse(
            'PATCH',
            '/product/' . $id,
            ['json' => $product],
        );
    }

    #[Route('/test/products/delete/{id}/{token}')]
    public function delete(int $id, string $token): Response
    {
        $this->accessToken = $token;

        return $this->getResponse(
            'DELETE',
            '/product/' . $id,
        );
    }
}