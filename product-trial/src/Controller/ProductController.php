<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', methods: ['POST'], format: 'json')]
    public function add(
        #[MapRequestPayload] Product $product,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->checkAdminAccess();

        $product->setCreatedAt(time());

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'ok',
            'result' => ['id' => $product->getId()],
        ]);
    }

    #[Route('/products', methods: ['GET'], format: 'json')]
    public function list(
        EntityManagerInterface $entityManager
    ): Response
    {
        $repository = $entityManager->getRepository(Product::class);

        return new JsonResponse([
            'status' => 'ok',
            'result' => $repository->getList(),
        ]);
    }

    #[Route('/product/{id}', methods: ['GET'], format: 'json')]
    public function get(
        int $id,
        EntityManagerInterface $entityManager
    ): Response
    {
        $repository = $entityManager->getRepository(Product::class);

        $product = $repository->get($id);
        if (!$product) {
            throw $this->createNotFoundException();
        }

        return new JsonResponse([
            'status' => 'ok',
            'result' => $product,
        ]);
    }

    #[Route('/product/{id}', methods: ['PATCH'], format: 'json')]
    public function update(
        int $id,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response
    {
        $this->checkAdminAccess();

        $repository = $entityManager->getRepository(Product::class);

        /** @var Product $product */
        $product = $repository->find($id);
        if (!$product) {
            throw $this->createNotFoundException();
        }

        $payload = $request->getPayload();

        if ($payload->has('code')) {
            $product->setCode($payload->get('code'));
        }

        if ($payload->has('name')) {
            $product->setName($payload->get('name'));
        }

        if ($payload->has('description')) {
            $product->setDescription($payload->get('description'));
        }

        // ...

        $product->setUpdatedAt(time());

        $entityManager->flush();

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }

    #[Route('/product/{id}', methods: ['DELETE'], format: 'json')]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->checkAdminAccess();

        $repository = $entityManager->getRepository(Product::class);

        $product = $repository->find($id);
        if ($product) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }

    private function checkAdminAccess()
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getEmail() != 'admin@admin.com') {
            throw $this->createAccessDeniedException();
        }
    }
}