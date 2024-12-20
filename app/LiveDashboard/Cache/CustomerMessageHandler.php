<?php declare(strict_types=1);

namespace App\LiveDashboard\Cache;

use App\LiveDashboard\Data\CustomerMessageData;

final class CustomerMessageHandler
{
    public const CACHE_KEY_PREFIX = 'msg';

    public function pullMessage(string $sessionId): ?CustomerMessageData
    {
        $data = cache()->pull($this->buildCacheKey($sessionId));

        if (is_null($data)) {
            return null;
        }

        return new CustomerMessageData($data);
    }

    public function storeMessage(string $sessionId, CustomerMessageData $message): void
    {
        cache([$this->buildCacheKey($sessionId) => (array) $message], now()->addMinutes(10));
    }

    private function buildCacheKey(string $sessionId): string
    {
        return self::CACHE_KEY_PREFIX . ':' . $sessionId;
    }
}
