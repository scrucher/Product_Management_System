<?php

namespace App\DataTransferObject;

class ProductDTO
{
    public $name;
    public $price;
    public $sku;
    public $status;
    public $currency;
    public $variations;

    public static function fromArray(array $validatedData): self
    {
        $dto = new self();
        $dto->name = $validatedData['name'] ?? '';
        $dto->price = $validatedData['price'] ?? 0;
        $dto->sku = $validatedData['sku'] ?? '';
        $dto->status = $validatedData['status'] ?? '';
        $dto->currency = $validatedData['currency'] ?? 'USD';
        $dto->variations = $validatedData['variations'] ?? null;

        return $dto;
    }
}
