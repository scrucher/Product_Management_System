<?php
namespace App\Utility;

use App\Services\ProductService;
use App\Parsers\CSVParser;
use App\Parsers\FilePath;
use App\DataTransferObject\ProductDTO;
use App\Factories\ProductDataFactory;
use Illuminate\Support\Facades\DB;

class ImportProducts
{
    private $productService;
    private $parser;
    private $filePath;
    private $productDataFactory;

    public function __construct(
        ProductService $productService,
        CSVParser $parser,
        FilePath $filePath,
        ProductDataFactory $productDataFactory
    ) {
        $this->productService = $productService;
        $this->parser = $parser;
        $this->filePath = $filePath->getPath('products.csv');
        $this->productDataFactory = $productDataFactory;
    }

    public function import()
    {
        DB::beginTransaction();
        try {
            set_time_limit(300); // Extend execution time
            $lines = $this->parser->parse($this->filePath);
            $productIdsInFile = [];

            foreach ($lines as $fields) {
                $productId = $fields[0];
                $productIdsInFile[] = $productId;

                $data = $this->productDataFactory->create($fields);
                $productDTO = ProductDTO::fromArray($data);

                $this->productService->saveOrUpdateProduct($productDTO);
            }

            // Soft delete products not in the current file
            $this->productService->softDeleteOutdatedProducts($productIdsInFile);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
