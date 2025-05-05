<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductCacheService;

final class ProductObserver
{
    private ProductCacheService $productCacheService;

    public function __construct(ProductCacheService $productCacheService)
    {
        $this->productCacheService = $productCacheService;
    }

    public function created(Product $product): void
    {
        $this->productCacheService->clearProductsCache();
    }

    public function updated(Product $product): void
    {
        $this->productCacheService->clearProductsCache();
    }

    public function deleted(Product $product): void
    {
        $this->productCacheService->clearProductsCache();
    }
}
