<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Url;
use App\Models\UrlActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class UrlActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UrlActivity::class;

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
            'type' => $this->faker->randomElement($array = array ('Copied', 'Shared')),
        ];
    }
}
