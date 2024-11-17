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
        $product = new Product($productDTO->toArray());
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

    public function softDeleteProductsNotInList( $productIdsInFile)
    {
        Product::whereNotIn('id', $productIdsInFile)
            ->update(['status' => 'deleted', 'deleted_at' => now()]);
    }

    public function saveOrUpdateProductsBatch($productDTOs): void
    {
        $dataToInsert = [];
        $updateQueries = [];

        foreach ($productDTOs as $productDTO) {
            $data = [
                'id' => $productDTO->id ?? null,
                'name' => $productDTO->name,
                'price' => $productDTO->price,
                'sku' => $productDTO->sku,
                'status' => $productDTO->status,
                'currency' => $productDTO->currency,
                'quantity' => $productDTO->quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $dataToInsert[] = $data;

            // Build an update query for each row
            $updateQueries[] = "
                INSERT INTO products (id, name, price, sku, status, currency, quantity, created_at, updated_at)
                VALUES (:id, :name, :price, :sku, :status, :currency, :quantity, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                price = VALUES(price),
                sku = VALUES(sku),
                status = VALUES(status),
                currency = VALUES(currency),
                quantity = VALUES(quantity),
                updated_at = VALUES(updated_at)
            ";
        }

        // Execute the batch insert and update using raw query
        DB::transaction(function () use ($updateQueries, $dataToInsert) {
            foreach ($updateQueries as $index => $query) {
                DB::statement($query, $dataToInsert[$index]);
            }
        });
    }
}
