<?php
use Mockery;
use Tests\TestCase; // Correct namespace for Laravel test case
use App\Utility\ImportProducts;
use App\Services\ProductService;
use App\Parsers\CSVParser;
use App\Parsers\FilePath;
use App\DataTransferObject\ProductDTO;
use App\Factories\ProductDataFactory;
use App\Utility\PriorityQueue;
use App\Utility\Actions\CreateProductAction;

class ImportProductsTest extends TestCase
{
    public function testImport()
    {
        // Mock dependencies
        $productServiceMock = Mockery::mock(ProductService::class);
        $csvParserMock = Mockery::mock(CSVParser::class);
        $filePathMock = Mockery::mock(FilePath::class);
        $productDataFactoryMock = Mockery::mock(ProductDataFactory::class);
        $queueMock = Mockery::mock(PriorityQueue::class);

        // Mock filePath behavior
        $filePathMock->shouldReceive('getPath')
            ->once()
            ->with('products.csv')
            ->andReturn('path/to/products.csv');

        // Mock parser
        $csvParserMock->shouldReceive('parse')
            ->once()
            ->with('path/to/products.csv')
            ->andReturn([
                ['123', 'Product 1', 'desc', 'cat', 'price', 'qty', 'status', 'date'],
            ]);

        // Mock productService behaviors
        $productServiceMock->shouldReceive('getProduct')
            ->once()
            ->with('123')
            ->andReturn(null);

        // Mock productDataFactory behavior
        $productDataFactoryMock->shouldReceive('create')
            ->once()
            ->with(['123', 'Product 1', 'desc', 'cat', 'price', 'qty', 'status', 'date'])
            ->andReturn(['id' => '123', 'name' => 'Product 1']);

        $queueMock->shouldReceive('enqueue')
            ->once()
            ->with(Mockery::type(CreateProductAction::class), 1);

        $queueMock->shouldReceive('isEmpty')
            ->andReturn(false, true); // Queue has one item, then becomes empty

        $queueMock->shouldReceive('dequeue')
            ->once()
            ->andReturn(['action' => Mockery::mock(CreateProductAction::class, function ($mock) {
                $mock->shouldReceive('execute')->once()->andReturn(['success' => true]);
            })]);

        // Instantiate ImportProducts with mocks
        $importProducts = new ImportProducts(
            $productServiceMock,
            $csvParserMock,
            $filePathMock,
            $productDataFactoryMock,
            $queueMock
        );

        // Execute and assert
        $results = $importProducts->import();
        $this->assertCount(1, $results); // Expecting 1 product created
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
