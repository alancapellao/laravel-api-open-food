<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductRepository
{
        public function paginate(int $perPage, int $page): array;

        public function find($code): ?Product;

        public function create(array $data): Product;

        public function update($code, array $data): Product;

        public function delete($code): void;
}
