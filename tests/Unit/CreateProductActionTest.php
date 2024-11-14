<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\Services\ProductService;
use App\DataTransferObject\ProductDTO;
use App\Utility\Actions\CreateProductAction;

class CreateProductActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testExecute()
    {
        // Mock ProductService and ProductDTO
        $productService = Mockery::mock(ProductService::class);
        $productDTO = Mockery::mock(ProductDTO::class);

        $productService->shouldReceive('createProduct')
            ->once()
            ->with($productDTO)
            ->andReturn(true);

        // Create the action
        $createAction = new CreateProductAction($productDTO, $productService);

        // Execute the action
        $result = $createAction->execute();

        // Assert the result
        $this->assertTrue($result);
    }
}
