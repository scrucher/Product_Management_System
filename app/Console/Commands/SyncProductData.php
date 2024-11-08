<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class SyncProductData extends Command
{
    // Command signature
    protected $signature = 'sync:product-data';
    protected $description = 'Synchronize product data from a third-party API daily';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Make a request to the third-party API
            $response = Http::get('https://third-party-api.com/products');

            if ($response->successful()) {
                $products = $response->json();

                foreach ($products as $productData) {
                    // Update or create product
                    Product::updateOrCreate(
                        ['sku' => $productData['sku']], // Match existing product by SKU
                        [
                            'name' => $productData['name'],
                            'price' => $productData['price'],
                            'status' => $productData['status'],
                            'currency' => $productData['currency'],
                            // Add other fields as necessary
                        ]
                    );
                }

                $this->info('Product data synchronized successfully.');
                Log::info('Product data synchronized successfully.');
            } else {
                $this->error('Failed to fetch product data.');
                Log::error('Failed to fetch product data: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('Error during product data sync: ' . $e->getMessage());
        }
    }
}
