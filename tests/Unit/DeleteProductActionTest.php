<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\Services\ProductService;
use App\Utility\Actions\DeleteProductAction;

class DeleteProductActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testExecute()
    {
        // Mock ProductService
        $productService = Mockery::mock(ProductService::class);

        $productService->shouldReceive('deleteProduct')
            ->once()
            ->with('123')
            ->andReturn(true);

        // Create the action
        $deleteAction = new DeleteProductAction('123', $productService);

        // Execute the action
        $result = $deleteAction->execute();

        // Assert the result
        $this->assertTrue($result);
    }
}
