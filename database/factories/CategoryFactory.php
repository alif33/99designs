<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->text(8);

        return [
            'category_name' => $name,
            'category_slug' => $name,
            'category_icon' => 'image',
            'category_image' => 'image',
        ];
    }
}
