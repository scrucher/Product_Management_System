<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SyncProductDataTest extends TestCase
{
    public function test_sync_product_data_successful()
    {
        // Mock the HTTP response for a successful data sync
        $products = [
            [
                'id' => 1,
                'name' => 'Product A',
                'price' => 100,
                'variations' => [
                    ['id' => 1, 'color' => 'Red', 'size' => 'M', 'quantity' => 10, 'availability' => true]
                ]
            ]
        ];

        Http::shouldReceive('get')
            ->once()
            ->andReturnSelf();

        Http::shouldReceive('successful')
            ->once()
            ->andReturn(true);

        Http::shouldReceive('json')
            ->once()
            ->andReturn($products);

        // Expect an info log to be written upon success
        Log::shouldReceive('info')->once()->with('Product data synchronized successfully.');

        $this->artisan('sync:product-data')->assertExitCode(0);
    }

    public function test_sync_product_data_failed()
    {
        // Mock the HTTP response for a failed data sync
        Http::shouldReceive('get')
            ->once()
            ->andReturnSelf();

        Http::shouldReceive('successful')
            ->once()
            ->andReturn(false);

        Http::shouldReceive('status')
            ->once()
            ->andReturn(500);

        // Expect an error log to be written upon failure
        Log::shouldReceive('error')->once()->with('Failed to fetch product data: 500');

        $this->artisan('sync:product-data')->assertExitCode(1);
    }
}
