<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'title'      => $title,
            'slug'       => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(4),
            'body'       => $this->faker->paragraphs(5, true),
            'view_count' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
