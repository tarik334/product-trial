<?php
namespace App\Controller;

use App\Entity\User;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BasketController extends AbstractController
{
    public function __construct(
        private CacheItemPoolInterface $cache
    ) {}

    #[Route('/basket', methods: ['GET'], format: 'json')]
    public function get(): Response
    {
        return new JsonResponse([
            'status' => 'ok',
            'result' => $this->getBasket(),
        ]);
    }

    #[Route('/basket/{id}/{quantity}', methods: ['POST'], format: 'json')]
    public function add(int $id, int $quantity): Response
    {
        $basket = $this->getBasket();

        if (isset($basket[$id])) {
            $basket[$id] += $quantity;
        } else {
            $basket[$id] = $quantity;
        }

        $this->saveBasket($basket);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }

    #[Route('/basket/{id}/{quantity}', methods: ['DELETE'], format: 'json')]
    public function delete(int $id, int $quantity): Response
    {
        $basket = $this->getBasket();

        if (isset($basket[$id])) {
            $basket[$id] -= $quantity;
            if ($basket[$id] <= 0) {
                unset($basket[$id]);
            }
        }

        $this->saveBasket($basket);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }

    #[Route('/basket', methods: ['DELETE'], format: 'json')]
    public function free(): Response
    {
        $this->saveBasket([]);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }

    private function getBasket(): array
    {
        /** @var User $user */
        $user = $this->getUser();

        $item = $this->cache->getItem($user->getId() . '_basket');

        if (!$item->isHit()) {
            return [];
        }

        return $item->get();
    }

    private function saveBasket(array $basket)
    {
        /** @var User $user */
        $user = $this->getUser();

        $item = $this->cache->getItem($user->getId() . '_basket');
        $item->set($basket);
        $item->expiresAfter(3600*12);
        $this->cache->save($item);
    }
}