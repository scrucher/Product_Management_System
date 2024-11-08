<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductVariations;
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
        if($product->save()){
            if($productDTO->variations){
                foreach($productDTO->variations as $variation){
                    $productVariation = new ProductVariations();
                    $productVariation->product_id = $product->id;
                    $productVariation->size = $variation['size'];
                    $productVariation->color = $variation['color'];
                    $productVariation->price = $variation['price'];
                    $productVariation->quantity = $variation['quantity'];
                    $productVariation->save();
                    }
                }
            }

        $result = Product::find($product->id)->product_variations->where('id', $product->id)->first();
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
