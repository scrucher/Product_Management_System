<?php
namespace App\Factories;

class ProductDataFactory
{
    public static function create(array $fields): \stdClass
    {
        $data = new \stdClass();
        $data->name = $fields[4] ?? '';
        $data->price = $fields[10] ?? '';
        $data->sku = $fields[5] ?? '';
        $data->status = $fields[6] ?? '';
        $data->currency = $fields[11] ?? '';
        $data->variations = $fields[9] ?? '';

        return $data;
    }
}
