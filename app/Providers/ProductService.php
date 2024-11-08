<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use App\DataTransferObject\ProductDTO;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProduct(ProductDTO $productDTO)
    {
        return $this->productRepository->save($productDTO);
    }

    public function getProducts()
    {
        return $this->productRepository->getAll();
    }

    public function getProduct($id)
    {
        return $this->productRepository->findById($id);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }
}
