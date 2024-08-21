<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepository;

class EloquentProductRepository implements ProductRepository
{
        public function paginate(int $perPage, int $page): array
        {
                return Product::paginate($perPage, ['*'], 'page', $page)->toArray();
        }

        public function find($code): ?Product
        {
                return $this->getProductByCode($code);
        }

        public function create(array $data): Product
        {
                return Product::create($data);
        }

        public function update($product, array $data): Product
        {
                $product->update($data);
                return $product;
        }

        public function delete($product): void
        {
                $product->update(['status' => 'trash']);
        }

        private function getProductByCode($code): ?Product
        {
                return Product::where('code', $code)->first();
        }
}
