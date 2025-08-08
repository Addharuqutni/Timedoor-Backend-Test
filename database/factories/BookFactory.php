<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;
use App\Models\BookCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => Author::inRandomOrder()->first()->id ?? Author::factory(),
            'book_category_id' => BookCategory::inRandomOrder()->first()->id ?? BookCategory::factory(),
        ];
    }
}
