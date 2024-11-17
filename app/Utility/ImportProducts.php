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

            // Parse and process in chunks to handle large data efficiently
            $lines = $this->parser->parse($this->filePath);
            $productIdsInFile = [];
            $batchSize = 100; // Adjust batch size based on expected data volume
            $batchData = [];
            $isFirstInsert = true;

            foreach ($lines as $index => $fields) {
                $productId = $fields[0];
                $productIdsInFile[] = $productId;

                $data = $this->productDataFactory->create($fields);
                $productDTO = ProductDTO::fromArray($data);
                $batchData[] = $productDTO;

                // Introduce delay between first and second insert
                if ($index === 1 && $isFirstInsert) {
                    sleep(2);
                    $isFirstInsert = false;
                }

                // Insert in batches
                if (count($batchData) >= $batchSize) {
                    $data = ProductDTO::fromArray($batchData);
                    $this->productService->saveOrUpdateBatch($data);
                    $batchData = []; // Reset batch
                }
            }

            // Insert remaining data
            if (!empty($batchData)) {
                $data = ProductDTO::fromArray($batchData);
                if($data == []){
                    throw new \Exception('data is not an array');
                }
                $this->productService->saveOrUpdateBatch($data);
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
