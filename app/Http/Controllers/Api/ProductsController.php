<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer',
            'per_page' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        try {
            $products = $this->productService->paginateProducts($perPage, $page);
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching products.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
    {
        try {
            $product = $this->productService->createProduct($request->validated());
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the product.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code)
    {
        try {
            $product = $this->productService->getProductByCode($code);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the product.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductFormRequest $request, $code)
    {
        try {
            $product = $this->productService->updateProduct($code, $request->validated());
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the product.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code)
    {
        try {
            $this->productService->deleteProduct($code);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the product.'], 500);
        }
    }
}
