<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ProductVariations;

class ProductVariationsService extends ServiceProvider
{

    public function create($data, $product_id){
        return ProductVariations::create([
            'product_id' => $product_id,
            'size' => $data['size'],
            'color' => $data['color'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
        ]);
    }
}
