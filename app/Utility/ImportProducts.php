<?php

namespace App\Utility;

use App\Services\ProductService;
use App\Parsers\CSVParser;
use App\Parsers\FilePath;
use App\DataTransferObject\ProductDTO;
use App\Factories\ProductDataFactory;

class ImportProducts
{
    private $productService;
    private $parser;
    private $filePath;
    private $productDataFactory;

    public function __construct(ProductService $productService, CSVParser $parser, FilePath $filePath, ProductDataFactory $productDataFactory)
    {
        $this->productService = $productService;
        $this->parser = $parser;
        $this->filePath = $filePath->getPath('products.csv');
        $this->productDataFactory = $productDataFactory;
    }

    public function import()
    {
        $lines = $this->parser->parse($this->filePath);
        $results = [];

        foreach ($lines as $fields) {
            $productId = $fields[0];
            $product = $this->productService->getProduct($productId);

            if (!$product) {
                $data = $this->productDataFactory->create($fields);
            } else {
                try {
                    $this->productService->deleteProduct($productId);
                } catch (\Exception $e) {
                    echo 'Error deleting existing product: ' . $e->getMessage() . PHP_EOL;
                }
            }

            try {
                $productDTO = ProductDTO::fromArray($data);
                $result = $this->productService->createProduct($productDTO);
                $results[] = $result;
            } catch (\Exception $e) {
                echo 'Error creating/updating product: ' . $e->getMessage() . PHP_EOL;
            }
        }

        return $results;
    }
}
