<?php declare(strict_types=1);

namespace App\ProgressBar\Cache;

use App\ProgressBar\Data\BackgroundRequest;

final class BackgroundRequestHandler
{
    public const CACHE_KEY_PREFIX = 'rqid';

    public function fetchRequest(string $requestId): ?BackgroundRequest
    {
        $data = cache($this->buildCacheKey($requestId));

        if (is_null($data)) {
            return null;
        }

        return new BackgroundRequest(...$data);
    }

    public function storeRequest(BackgroundRequest $request): void
    {
        cache([$this->buildCacheKey($request->requestId) => (array) $request], now()->addMinutes(10));
    }

    private function buildCacheKey(string $requestId): string
    {
        return self::CACHE_KEY_PREFIX . ':' . $requestId;
    }
}
