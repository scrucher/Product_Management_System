<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility\ImportProducts;

class ProductController extends Controller
{
    public function __construct(ImportProducts $importProducts){
        $this->importProducts = $importProducts;
    }

    public function show(){
        $product = $this->importProducts->import();
        return response()->json(['data' => $product], 201);
    }
}
