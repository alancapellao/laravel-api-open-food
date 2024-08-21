<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Interfaces\ProductService as ProductServiceInterface;
use App\Repositories\EloquentProductRepository as ProductRepository;

class ProductService implements ProductServiceInterface
{
        protected $productRepository;

        public function __construct(ProductRepository $productRepository)
        {
                $this->productRepository = $productRepository;
        }

        public function paginateProducts(int $perPage, int $page): array
        {
                $perPage = max(1, $perPage);
                $page = max(1, $page);

                return $this->productRepository->paginate($perPage, $page);
        }

        public function getProductByCode($code): ?Product
        {
                return $this->productRepository->find($code);
        }

        public function createProduct(array $data): Product
        {
                return $this->productRepository->create($data);
        }

        public function updateProduct($code, array $data): Product
        {
                $product = $this->productRepository->find($code);

                if (!$product) {
                        throw new \Exception('Product not found.');
                }

                return $this->productRepository->update($product, $data);
        }

        public function deleteProduct($code): void
        {
                $product = $this->productRepository->find($code);

                if (!$product) {
                        throw new \Exception('Product not found.');
                }

                $this->productRepository->delete($product);
        }
}
