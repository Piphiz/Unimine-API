<?php

namespace Database\Seeders;

use App\Models\UrlActivity;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UrlSeeder::class);
        $this->call(UrlEntriesSeeder::class);
        $this->call(UrlActivitiesSeeder::class);
        \App\Models\User::factory(1)->create();
    }
}
