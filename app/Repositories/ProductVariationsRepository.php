<?php

namespace App\Repositories;

use App\Models\ProductVariations;

class ProductVariationsRepository
{
    public function save($variation, $product_id)
    {
        try {
            $productVariation = new ProductVariations();
            $productVariation->product_id = $product_id;
            $productVariation->size = $variation['size'];
            $productVariation->color = $variation['color'];
            $productVariation->attributes = $variation['attributes'];
            $productVariation->quantity = $variation['quantity'];
            $productVariation->availability = $variation['availability'];
            $productVariation->save();

            return $productVariation;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
