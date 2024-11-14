<?php
namespace App\Services;

use App\Repositories\ProductRepository;
use App\DataTransferObject\ProductDTO;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function saveOrUpdateProduct(ProductDTO $productDTO)
    {
        $existingProduct = $this->productRepository->findBySku($productDTO->sku);

        if ($existingProduct) {
            $this->productRepository->update($existingProduct->id, $productDTO);
        } else {
            $this->productRepository->save($productDTO);
        }
    }

    public function softDeleteOutdatedProducts(array $productIdsInFile)
    {
        $this->productRepository->softDeleteProductsNotInList($productIdsInFile);
    }

    public function getProducts(): array
    {
        return $this->productRepository->getAll();
    }

    public function getProduct($id)
    {
        return $this->productRepository->findById($id);
    }
}
