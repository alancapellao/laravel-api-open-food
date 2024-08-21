<?php

namespace App\Services\Interfaces;

use App\Models\Product;

interface ProductService
{
    public function paginateProducts(int $perPage, int $page): array;

    public function getProductByCode($code): ?Product;

    public function createProduct(array $data): Product;

    public function updateProduct(int $code, array $data): Product;

    public function deleteProduct(int $code): void;
}