<?php

namespace App\DataTransferObject;

class ProductDTO
{
    public string $name;
    public float $price;
    public string $sku;
    public string $status;
    public string $currency;
    public ?array $variations;
    public int $quantity;

    public static function fromArray(array $validatedData): self
    {
        $dto = new self();
        $dto->name = $validatedData['name'] ?? '';
        $dto->price = $validatedData['price'] ?? 0.0;
        $dto->sku = $validatedData['sku'] ?? '';
        $dto->status = $validatedData['status'] ?? '';
        $dto->currency = $validatedData['currency'] ?? 'USD';
        $dto->variations = $validatedData['variations'] ?? null;
        $dto->quantity = $validatedData['quantity'] ?? 0;

        return $dto;
    }
}
