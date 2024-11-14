<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Factories\ProductDataFactory;

class ProductDataFactoryTest extends TestCase
{
    public function test_create_with_valid_fields()
    {
        $fields = [
            0 => '1',                  // ID (not used in factory)
            1 => 'Product Name',       // Name
            2 => 'SKU123',             // SKU
            3 => '19.99',              // Price
            4 => 'USD',                // Currency
            5 => json_encode([         // Variations
                ['size' => 'M', 'color' => 'Red'],
                ['size' => 'L', 'color' => 'Blue']
            ]),
            6 => '10',                 // Quantity
            7 => 'active',             // Status
        ];

        $result = ProductDataFactory::create($fields);

        $expected = [
            'name' => 'Product Name',
            'sku' => 'SKU123',
            'price' => 19.99,
            'currency' => 'USD',
            'variations' => [
                ['size' => 'M', 'color' => 'Red', 'quantity' => 10],
                ['size' => 'L', 'color' => 'Blue', 'quantity' => 10],
            ],
            'status' => 'active',
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_create_with_missing_optional_fields()
    {
        $fields = [
            1 => 'Product Name',
            2 => 'SKU123',
            3 => '19.99',
        ];

        $result = ProductDataFactory::create($fields);

        $expected = [
            'name' => 'Product Name',
            'sku' => 'SKU123',
            'price' => 19.99,
            'currency' => 'USD',
            'variations' => [],  // Default empty array
            'status' => '',      // Default empty string
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_create_with_invalid_json_variations()
    {
        $fields = [
            1 => 'Product Name',
            5 => 'invalid-json',
        ];

        $result = ProductDataFactory::create($fields);

        $expected = [
            'name' => 'Product Name',
            'sku' => '',
            'price' => 0.0,
            'currency' => 'USD',
            'variations' => [],  // Default to empty array if JSON is invalid
            'status' => '',
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_create_with_empty_fields()
    {
        $fields = [];

        $result = ProductDataFactory::create($fields);

        $expected = [
            'name' => '',
            'sku' => '',
            'price' => 0.0,
            'currency' => 'USD',
            'variations' => [],
            'status' => '',
        ];

        $this->assertEquals($expected, $result);
    }
}
