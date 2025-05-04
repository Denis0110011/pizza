<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

final class ProductCacheService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY_PREFIX = 'product';
    private const CACHE_KEY_ALL = 'product:all:page:';

    public function getPaginatedProducts(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = self::CACHE_KEY_ALL . $page . ':perPage:' . $perPage;

        return Cache::remember($cacheKey, self::CACHE_TTL, static fn() => Product::paginate($perPage));
    }

    public function getProductById(string $id): ?Product
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $id;

        return Cache::remember($cacheKey, self::CACHE_TTL, static fn() => Product::find($id));
    }
}
