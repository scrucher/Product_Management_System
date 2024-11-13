<?php

namespace App\Repositories;

use App\Models\Product;
use App\DataTransferObject\ProductDTO;
use App\Repositories\ProductVariationsRepository;

class ProductRepository
{
    protected $productVariationsRepository;

    public function __construct(ProductVariationsRepository $productVariationsRepository)
    {
        $this->productVariationsRepository = $productVariationsRepository;
    }

    public function save(ProductDTO $productDTO)
    {
        if ($productDTO->status == 'deleted') {
            return;
        }

        $product = new Product();
        $product->name = $productDTO->name;
        $product->price = $productDTO->price;
        $product->sku = $productDTO->sku;
        $product->status = $productDTO->status;
        $product->currency = $productDTO->currency;
        $product->quantity = $productDTO->quantity;
        $product->save();

        if ($productDTO->variations) {
            foreach ($productDTO->variations as $variation) {
                try {
                    $this->productVariationsRepository->save($variation, $product->id);
                } catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }

        return Product::with('product_variations')->find($product->id);
    }

    public function getAll()
    {
        return Product::with('product_variations')->get();
    }

    public function findById($id)
    {
        return Product::with('product_variations')->find($id);
    }

    public function delete($id)
    {
        return Product::destroy($id);
    }
}
