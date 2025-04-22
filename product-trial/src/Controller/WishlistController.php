<?php
namespace App\Controller;

use App\Entity\User;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishlistController extends AbstractController
{
    public function __construct(
        private CacheItemPoolInterface $cache
    ) {}

    #[Route('/wishlist', methods: ['GET'], format: 'json')]
    public function get(): Response
    {
        return new JsonResponse([
            'status' => 'ok',
            'result' => $this->getWishlist(),
        ]);
    }

    #[Route('/wishlist/{id}', methods: ['POST'], format: 'json')]
    public function add(int $id): Response
    {
        $wishlist = $this->getWishlist();

        $wishlist[$id] = $id;

        $this->saveWishlist($wishlist);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }

    #[Route('/wishlist/{id}', methods: ['DELETE'], format: 'json')]
    public function delete(int $id): Response
    {
        $wishlist = $this->getWishlist();

        unset($wishlist[$id]);

        $this->saveWishlist($wishlist);

        return new JsonResponse([
            'status' => 'ok',
        ]);
    }

    private function getWishlist(): array
    {
        /** @var User $user */
        $user = $this->getUser();

        $item = $this->cache->getItem($user->getId() . '_wishlist');

        if (!$item->isHit()) {
            return [];
        }

        return $item->get();
    }

    private function saveWishlist(array $wishlist)
    {
        /** @var User $user */
        $user = $this->getUser();

        $item = $this->cache->getItem($user->getId() . '_wishlist');
        $item->set($wishlist);
        $this->cache->save($item);
    }
}