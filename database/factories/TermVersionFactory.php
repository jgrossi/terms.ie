<?php

namespace Database\Factories;

use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermVersionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'term_id' => Term::factory(),
            'version' => 1,
            'body'    => fake()->paragraphs(2, true),
        ];
    }
}
