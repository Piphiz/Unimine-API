<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Url;
use App\Models\UrlEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class UrlEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UrlEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url_id' => Url::inRandomOrder()->first(),
            'ip' => $this->faker->ipv4(),
        ];
    }
}
