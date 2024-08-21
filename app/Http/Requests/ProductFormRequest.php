<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'status' => 'required|in:draft,trash,published',
            'url' => 'string',
            'creator' => 'string',
            'product_name' => 'required|string',
            'quantity' => 'string',
            'brands' => 'string',
            'categories' => 'string',
            'labels' => 'string',
            'cities' => 'string',
            'purchase_places' => 'string',
            'stores' => 'string',
            'ingredients_text' => 'string',
            'traces' => 'string',
            'serving_size' => 'string',
            'serving_quantity' => 'numeric',
            'nutriscore_score' => 'integer',
            'nutriscore_grade' => 'string',
            'main_category' => 'string',
            'image_url' => 'string',
        ];
    }
}
