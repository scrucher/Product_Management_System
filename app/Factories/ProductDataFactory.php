<?php

namespace App\Factories;

class ProductDataFactory
{
    public static function create(array $fields): array
    {
        $variations = json_decode($fields[5] ?? '[]', true);

        // Ensure variations defaults to an array
        if (!is_array($variations)) {
            $variations = [];
        }

        foreach ($variations as &$variation) {
            $variation['quantity'] = (int) ($fields[6] ?? 0);
        }

        return [
            'name' => $fields[1] ?? '',
            'sku' => $fields[2] ?? '',
            'price' => (float) ($fields[3] ?? 0),
            'currency' => $fields[4] ?? 'USD',
            'variations' => $variations,
            'status' => $fields[7] ?? '',
        ];
    }
}
