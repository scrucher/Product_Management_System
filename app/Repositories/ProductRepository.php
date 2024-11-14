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
        $product = new Product($productDTO->fromArray());
        $product->save();

        $this->saveVariations($productDTO->variations, $product->id);
        return Product::with('product_variations')->find($product->id);
    }

    public function update($id, ProductDTO $productDTO)
    {
        $product = Product::findOrFail($id);
        $product->update($productDTO->toArray());

        // Clear existing variations before saving new ones
        $product->product_variations()->delete();
        $this->saveVariations($productDTO->variations, $id);
    }

    public function saveVariations(array $variations, $productId)
    {
        foreach ($variations as $variation) {
            $this->productVariationsRepository->save($variation, $productId);
        }
    }

    public function findBySku($sku)
    {
        return Product::where('sku', $sku)->first();
    }

    public function getAll()
    {
        return Product::with('product_variations')->get();
    }

    public function findById($id)
    {
        return Product::with('product_variations')->find($id);
    }

    public function softDeleteProductsNotInList(array $productIdsInFile)
    {
        Product::whereNotIn('id', $productIdsInFile)
            ->update(['status' => 'deleted', 'deleted_at' => now()]);
    }
}
