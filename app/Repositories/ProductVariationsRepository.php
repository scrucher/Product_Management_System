<?php
namespace App\Repositories;

use App\Models\ProductVariations;

class ProductVariationsRepository
{
    public function save(array $variation, $productId)
    {
        ProductVariations::create([
            'product_id' => $productId,
            'size' => $variation['size'] ?? null,
            'color' => $variation['color'] ?? null,
            'attributes' => $variation['attributes'] ?? null,
            'quantity' => $variation['quantity'] ?? 0,
            'availability' => $variation['availability'] ?? false,
        ]);
    }
}
