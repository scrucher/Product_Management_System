# Product Import and Management System

## Overview

The **Product Import and Management System** is designed to efficiently manage product data within a Laravel-based application. The system allows for the import of product data from CSV files, batch processing, and updating existing product records. Additionally, it includes functionality for managing product variations, performing soft deletions of outdated products, and ensuring data integrity through transactional processing.

## Features

- **Batch Import**: Handles large CSV file imports in batches, with the ability to update or insert new products based on SKU.
- **Product Variations**: Supports saving variations like size, color, and quantity.
- **Soft Deletion**: Marks products as deleted if they are no longer present in the imported file.
- **Transactional Integrity**: Utilizes database transactions to ensure data consistency and rollback in case of errors.
- **Configurable Batch Size**: Allows customization of batch size for importing and updating records.
- **Extended Execution Time**: Handles large files by extending PHP script execution time.


## Folder Structure

```plaintext
app/
│
├── Parsers/
│   ├── CSVParser.php       # Handles parsing of CSV files
│   ├── FilePath.php        # Provides the path to the CSV file
│
├── Repositories/
│   ├── ProductRepository.php    # Handles product-related database operations
│   └── ProductVariationsRepository.php   # Handles product variation-related database operations
│
├── Services/
│   ├── ProductService.php    # Main service for managing product-related actions
│
├── Utility/
│   ├── ImportProducts.php    # Handles the logic for importing and managing products
│
├── DataTransferObject/
│   ├── ProductDTO.php        # Defines the structure of product data transfer objects
│
├── Factories/
│   ├── ProductDataFactory.php  # Factory for creating ProductDTO objects from raw data
│
└── Models/
    ├── Product.php            # Eloquent model for the Product table
    └── ProductVariations.php  # Eloquent model for the ProductVariations table
```

Dependencies
This project requires the following dependencies:

Laravel Framework: The core PHP framework used for building the application.
Illuminate/Database: Laravel's database library for interacting with SQL databases.
PHP 7.3+: The minimum PHP version required for running this project.
Setup and Installation
Clone the Repository:

bash
Copy code
git clone https://github.com/your-repository-url.git
cd your-repository
Install Composer Dependencies:

bash
Copy code
composer install
Set up the .env file: Copy .env.example to .env and configure your database settings.

bash
Copy code
cp .env.example .env
php artisan key:generate
Run Migrations: Make sure to run the migrations to set up the database tables.

bash
Copy code
php artisan migrate
Set File Path for CSV: Ensure the correct file path is provided in the ImportProducts.php service to point to your CSV file.

How It Works
Importing Products
CSV File Parsing: The CSVParser class is responsible for parsing the CSV file and extracting rows of product data.
Data Transformation: The ProductDataFactory class transforms raw CSV data into structured arrays, which are converted into ProductDTO objects.
Product Insertion/Update:
If a product with the same SKU already exists, it is updated using the ProductService.
If the product does not exist, it is inserted into the database.
Batch Processing: Products are processed in batches of 100 (configurable), improving the efficiency of bulk inserts and updates.
Soft Deletion: After the import, the softDeleteOutdatedProducts method marks products that were not included in the file as deleted.
Classes Overview
## 1. ProductService
Handles the logic for saving, updating, and deleting products. It interacts with the ProductRepository to perform database operations.

```saveOrUpdateProduct(ProductDTO $productDTO): Saves or updates a product based on its SKU.```

```softDeleteOutdatedProducts(array $productIdsInFile): Marks products as deleted if they are not in the provided list.```

````getProducts(): Retrieves all products.````

````getProduct($id): Retrieves a single product by ID.````

````saveOrUpdateBatch($productDTOs): Saves or updates a batch of products.````
## 2. ImportProducts
Handles the import process, from reading the CSV file to saving the products in the database.


````import(): The main method for importing products from a CSV file. It reads the file, processes the data in batches, and performs soft deletions for outdated products.````

## 3. CSVParser
Responsible for reading and parsing the CSV file.

````parse(string $filePath): Parses the CSV file and returns an array of data rows.````

## 4. ProductDTO
Defines the data transfer object for a product. It provides a structure for representing product data within the application.

````fromArray(array $data): Creates a ProductDTO object from an array of data.````

## 5. ProductDataFactory
Transforms raw data into a ProductDTO object. This factory is used to map CSV rows into the correct structure for product DTOs.

````create(array $data): Converts raw product data into a structured array suitable for creating a ProductDTO.````

## 6. ProductRepository
Handles direct interactions with the database for product operations such as saving, updating, and retrieving products.

```save(ProductDTO $productDTO): Saves a new product to the database.```

```update($id, ProductDTO $productDTO): Updates an existing product.```

```findBySku($sku): Finds a product by SKU.```

```softDeleteProductsNotInList(array $productIdsInFile): Soft deletes products not found in the current import file.```

```saveOrUpdateProductsBatch(array $productDTOs): Saves or updates a batch of products.```


# Usage Example
Importing Products from a CSV File:

```
use App\Services\ProductService;
use App\Parsers\CSVParser;
use App\Parsers\FilePath;
use App\Factories\ProductDataFactory;
use App\Utility\ImportProducts;

$productService = app(ProductService::class);
$csvParser = app(CSVParser::class);
$filePath = app(FilePath::class);
$productDataFactory = app(ProductDataFactory::class);

$importProducts = new ImportProducts(
    $productService,
    $csvParser,
    $filePath,
    $productDataFactory
);

$importProducts->import(); // Initiates the import process
```
# Error Handling
If any errors occur during the import process, such as database failures or invalid data, the process is rolled back to ensure data consistency. Exceptions are thrown to notify the user of any issues.

