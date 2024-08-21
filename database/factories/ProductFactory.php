<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomNumber(8),
            'status' => $this->faker->randomElement(['draft', 'trash', 'published']),
            'imported_t' => $this->faker->dateTime(),
            'url' => $this->faker->url,
            'creator' => $this->faker->name,
            'created_t' => $this->faker->dateTime(),
            'last_modified_t' => $this->faker->dateTime(),
            'product_name' => $this->faker->word,
            'quantity' => $this->faker->word,
            'brands' => $this->faker->word,
            'categories' => $this->faker->word,
            'labels' => $this->faker->word,
            'cities' => $this->faker->word,
            'purchase_places' => $this->faker->word,
            'stores' => $this->faker->word,
            'ingredients_text' => $this->faker->text,
            'traces' => $this->faker->word,
            'serving_size' => $this->faker->word,
            'serving_quantity' => $this->faker->randomFloat(2, 0, 100),
            'nutriscore_score' => $this->faker->randomNumber(2),
            'nutriscore_grade' => $this->faker->word,
            'main_category' => $this->faker->word,
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
