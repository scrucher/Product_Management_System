<?php
namespace App\Repositories;

use App\Models\ProductVariations;

class ProductVariationsRepository
{
    public function save(array $variation, $productId)
    {
        ProductVariations::create([
            'product_id' => $productId,
            'size' => $variation['size'],
            'color' => $variation['color'],
            'attributes' => $variation['attributes'],
            'quantity' => $variation['quantity'],
            'availability' => $variation['availability'],
        ]);
    }
}
