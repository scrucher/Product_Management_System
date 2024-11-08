<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

Route::get('/products', function (){
    return ProductController::createProduct();
}); // create a new product
