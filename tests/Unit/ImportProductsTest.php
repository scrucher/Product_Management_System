<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Utility\ImportProducts;
use App\Services\ProductService;
use App\Parsers\CSVParser;
use App\Parsers\FilePath;
use App\Factories\ProductDataFactory;
use App\DataTransferObject\ProductDTO;
use Mockery;

class ImportProductsTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close(); // Close Mockery to avoid memory leaks
    }

    public function test_import_products_successful()
    {
        $productService = Mockery::mock(ProductService::class);
        $parser = Mockery::mock(CSVParser::class);
        $filePath = Mockery::mock(FilePath::class);
        $productDataFactory = Mockery::mock(ProductDataFactory::class);

        // Mock the file path and parsing
        $filePath->shouldReceive('getPath')->with('products.csv')->andReturn('/mock/path/products.csv');
        $parser->shouldReceive('parse')->with('/mock/path/products.csv')->andReturn([
            ['1', 'Product A', 'SKU1', 100, 'USD', '[]', 0, 'active'],  // Ensure this array has enough elements
        ]);

        $productService->shouldReceive('getProduct')->with('1')->andReturn(null);
        $productService->shouldReceive('createProduct')->andReturn(true);

        $productDataFactory->shouldReceive('create')->andReturn([
            'name' => 'Product A',
            'sku' => 'SKU1',
            'price' => 100,
            'currency' => 'USD',
            'variations' => [],
            'status' => 'active',
        ]);

        $importer = new ImportProducts($productService, $parser, $filePath, $productDataFactory);
        $results = $importer->import();

        $this->assertEquals([true], $results);  // Expected results
    }


    public function test_import_products_handles_existing_product()
    {
        $productService = Mockery::mock(ProductService::class);
        $parser = Mockery::mock(CSVParser::class);
        $filePath = Mockery::mock(FilePath::class);
        $productDataFactory = Mockery::mock(ProductDataFactory::class);

        $filePath->shouldReceive('getPath')->with('products.csv')->andReturn('/mock/path/products.csv');
        $parser->shouldReceive('parse')->with('/mock/path/products.csv')->andReturn([
            ['id' => '1', 'name' => 'Product A', 'sku' => 'SKU1', 'price' => 100]
        ]);

        $productService->shouldReceive('getProduct')->with('SKU1')->andReturn('existingProduct');
        $productService->shouldReceive('deleteProduct')->with('SKU1')->once();
        $productService->shouldReceive('createProduct')->andReturn(true);

        $productDataFactory->shouldReceive('create')->never();

        $importer = new ImportProducts($productService, $parser, $filePath, $productDataFactory);
        $results = $importer->import();

        $this->assertCount(1, $results);
        $this->assertTrue($results[0]);
    }

    public function test_import_products_handles_exceptions()
    {
        $productService = Mockery::mock(ProductService::class);
        $parser = Mockery::mock(CSVParser::class);
        $filePath = Mockery::mock(FilePath::class);
        $productDataFactory = Mockery::mock(ProductDataFactory::class);

        $filePath->shouldReceive('getPath')->with('products.csv')->andReturn('/mock/path/products.csv');
        $parser->shouldReceive('parse')->with('/mock/path/products.csv')->andReturn([
            ['id' => '1', 'name' => 'Product A', 'sku' => 'SKU1', 'price' => 100]
        ]);

        $productService->shouldReceive('getProduct')->with('SKU1')->andReturn(null);
        $productService->shouldReceive('createProduct')->andThrow(new \Exception('Create failed'));

        $productDataFactory->shouldReceive('create')->andReturn([
            'name' => 'Product A',
            'sku' => 'SKU1',
            'price' => 100
        ]);

        $importer = new ImportProducts($productService, $parser, $filePath, $productDataFactory);
        $results = $importer->import();

        $this->assertCount(0, $results); // No successful creations due to exception
    }
}
