<?php

namespace Database\Seeders;

use App\Models\UrlActivity;
use Illuminate\Database\Seeder;

class UrlActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UrlActivity::factory(10)->create();
    }
}
