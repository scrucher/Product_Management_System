<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class SyncProductData extends Command
{
    protected $signature = 'sync:product-data';
    protected $description = 'Synchronize product data from an external API';

    public function handle()
    {
        try {
            $response = Http::get('https://5fc7a13cf3c77600165d89a8.mockapi.io/api/v5/products');

            if ($response->successful()) {
                $products = $response->json();

                // Track existing products
                $existingProductIds = Product::pluck('id')->toArray();
                $receivedProductIds = [];

                foreach ($products as $productData) {
                    $receivedProductIds[] = $productData['id'];

                    // Update or create product with variations
                    $product = Product::updateOrCreate(
                        ['id' => $productData['id']],
                        ['name' => $productData['name'], 'price' => $productData['price'], 'status' => 'active']
                    );

                    // Update variations (assuming your database has a ProductVariation model/table)
                    foreach ($productData['variations'] as $variation) {
                        $product->variations()->updateOrCreate(
                            ['id' => $variation['id']],
                            [
                                'color' => $variation['color'],
                                'size' => $variation['size'],
                                'quantity' => $variation['quantity'],
                                'availability' => $variation['availability']
                            ]
                        );
                    }
                }

                // Soft delete outdated products
                $productsToDelete = array_diff($existingProductIds, $receivedProductIds);
                Product::whereIn('id', $productsToDelete)->update(['status' => 'deleted']);

                Log::info('Product data synchronized successfully.');
                return 0; // Success
            } else {
                Log::error('Failed to fetch product data: ' . $response->status());
                return 1; // Failure
            }
        } catch (\Exception $e) {
            Log::error('Error during product data sync: ' . $e->getMessage());
            return 1; // Failure on exception
        }
    }
}
