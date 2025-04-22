<?php
namespace App\Security;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private CacheItemPoolInterface $cache
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $item = $this->cache->getItem($accessToken);

        if (!$item->isHit()) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($item->get());
    }
}