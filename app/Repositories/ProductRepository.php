<?php

namespace App\Repositories;

use App\Models\Product;
use App\DataTransferObject\ProductDTO;

class ProductRepository
{
    public function save(ProductDTO $productDTO)
    {
        $product = new Product();
        $product->name = $productDTO->name;
        $product->price = $productDTO->price;
        $product->sku = $productDTO->sku;
        $product->status = $productDTO->status;
        $product->currency = $productDTO->currency;
        $product->variations = $productDTO->variations;
        $product->save();

        return $product;
    }

    public function getAll()
    {
        return Product::all();
    }

    public function findById($id)
    {
        return Product::find($id);
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }
}
