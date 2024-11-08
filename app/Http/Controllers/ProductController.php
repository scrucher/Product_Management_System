<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility\ImportProduct;

class ProductController extends Controller
{
    public function __construct(ImportProduct $importProduct){
        $this->importProduct = $importProduct;
    }

    public static function createProduct(){
        $product = $this->importProduct->import();
        return response()->json(['message' => 'Product created successfully!'], 201);
    }
}
