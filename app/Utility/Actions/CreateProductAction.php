<?php

namespace App\Utility\Actions;

use App\DataTransferObject\ProductDTO;

interface ProductActionInterface
{
    public function execute();
}

class CreateProductAction implements ProductActionInterface
{
    protected $productDTO;
    protected $productService;

    public function __construct(ProductDTO $productDTO, $productService)
    {
        $this->productDTO = $productDTO;
        $this->productService = $productService;
    }

    public function execute()
    {
        return $this->productService->createProduct($this->productDTO);
    }
}

class DeleteProductAction implements ProductActionInterface
{
    protected $productId;
    protected $productService;

    public function __construct($productId, $productService)
    {
        $this->productId = $productId;
        $this->productService = $productService;
    }

    public function execute()
    {
        return $this->productService->deleteProduct($this->productId);
    }
}
