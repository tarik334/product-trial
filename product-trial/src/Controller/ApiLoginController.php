<?php
namespace App\Controller;

use App\Entity\User;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/token', methods: ['POST'], name: 'api_login', format: 'json')]
    public function index(
        #[CurrentUser] ?User $user,
        CacheItemPoolInterface $cache,
    ): Response
    {
        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = bin2hex(random_bytes(32));

        $item = $cache->getItem($token);
        $item->set($user->getUserIdentifier());
        $item->expiresAfter(3600);
        $cache->save($item);

        return $this->json([
            'status' => 'ok',
            'result' => ['token' => $token],
        ]);
    }
}