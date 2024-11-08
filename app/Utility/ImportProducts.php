<?php

namespace App\Utility;

use App\Providers\ProductService;
use App\Factories\ProductDataFactory;
use App\Parsers\CSVParser;
use App\Parsers\FilePath;

class ImportProducts
{
    private $productService;
    private $parser;
    private $filePath;

    public function __construct(ProductService $productService, CSVParser $parser, FilePath $filePath)
    {
        $this->productService = $productService;
        $this->parser = $parser;
        $this->filePath = $filePath;
    }

    public function import()
    {
        $lines = $this->parser->parse($filePath);
        $results;

        foreach ($lines as $fields) {
            $productId = $fields[0];

            $product = $this->productService->getProduct($productId);

            if ($product) {
                try {
                    $this->productService->deleteProduct($productId);
                    echo 'Deleted existing product to be updated.' . PHP_EOL;
                } catch (\Exception $e) {
                    echo 'Error deleting existing product: ' . $e->getMessage() . PHP_EOL;
                }
            }

            $data = ProductDataFactory::create($fields);

            try {
                $results = $this->productService->createProduct($data);
            } catch (\Exception $e) {
                echo 'Error creating/updating product: ' . $e->getMessage() . PHP_EOL;
            }
            return $results;
        }
    }
}
