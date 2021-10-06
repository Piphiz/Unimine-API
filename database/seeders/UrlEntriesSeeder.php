<?php

namespace Database\Seeders;

use App\Models\UrlEntry;
use Illuminate\Database\Seeder;

class UrlEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UrlEntry::factory(10)->create();
    }
}
