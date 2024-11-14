<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ProductController;
use App\Utility\ImportProducts;
use Illuminate\Http\JsonResponse;
use Mockery;

class ProductControllerTest extends TestCase
{
    private $importProductsMock;
    private $productController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importProductsMock = Mockery::mock(ImportProducts::class);
        $this->productController = new ProductController($this->importProductsMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testShow(): void
    {
        $mockedProductData = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 100],
            ['id' => 2, 'name' => 'Product 2', 'price' => 200],
        ];

        $this->importProductsMock
            ->shouldReceive('import')
            ->once()
            ->andReturn($mockedProductData);

        $response = $this->productController->show();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->status());
        $this->assertEquals(['data' => $mockedProductData], $response->getData(true));
    }
}
