<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\ProductController;
use App\Utility\ImportProducts;
use App\Factories\ProductDataFactory;
use App\Parsers\CSVParser;
use App\Parsers\FilePath;
use App\Services\ProductService; // Correct ProductService namespace
use App\Repositories\ProductRepository; // Ensure this exists
use App\Repositories\ProductVariationsRepository; // Ensure this exists
use App\Utiiity\Actions\CreateProductAction; // Ensure this exists
use App\Utiiity\Actions\DeleteProductAction;
use App\Utility\PriorityQueue; // Ensure this exists
use App\DataTransferObject\ProductDTO; // Ensure this exists

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind ProductRepository
        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository($app->make(ProductVariationsRepository::class)); // Ensure proper instantiation
        });

        // Bind ProductService
        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService($app->make(ProductRepository::class));
        });

        // Bind ImportProducts
        $this->app->bind(ImportProducts::class, function ($app) {
            return new ImportProducts(
                $app->make(ProductService::class), // Pass correct ProductService instance
                $app->make(CSVParser::class),      // Resolve CSVParser
                $app->make(FilePath::class),      // Resolve FilePath
                $app->make(ProductDataFactory::class),
                $app->make(PriorityQueue::class) // Resolve
            );
        });
        $this->app->bind(CreateProductAction::class, function ($app) {
            return new CreateProductAction(
                $app->make(ProductService::class),
                $app->make(ProductDTO::class)
            );
        });
        $this->app->bind(DeleteProductAction::class, function ($app) {
            return new DeleteProductAction(
                $app->make(ProductService::class),
                $app->make(ProductDTO::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
