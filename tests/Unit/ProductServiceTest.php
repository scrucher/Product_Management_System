<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use App\DataTransferObject\ProductDTO;
use Mockery;

class ProductServiceTest extends TestCase
{
    private $productService;
    private $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = Mockery::mock(ProductRepository::class);
        $this->productService = new ProductService($this->productRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateProduct(): void
    {
        $productDTO = new ProductDTO([
            'name' => 'Test Product',
            'price' => 100.00,
            'description' => 'A test product description.',
        ]);

        $this->productRepository
            ->shouldReceive('save')
            ->once()
            ->with($productDTO)
            ->andReturn(true);

        $result = $this->productService->createProduct($productDTO);

        $this->assertTrue($result);
    }

    public function testGetProducts(): void
    {
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 100.00],
            ['id' => 2, 'name' => 'Product 2', 'price' => 200.00],
        ];

        $this->productRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($products);

        $result = $this->productService->getProducts();

        $this->assertEquals($products, $result);
    }

    public function testGetProduct(): void
    {
        $product = ['id' => 1, 'name' => 'Product 1', 'price' => 100.00];

        $this->productRepository
            ->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($product);

        $result = $this->productService->getProduct(1);

        $this->assertEquals($product, $result);
    }

    public function testDeleteProduct(): void
    {
        $this->productRepository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $this->productService->deleteProduct(1);

        $this->assertTrue($result);
    }
}
