<?php

namespace Database\Seeders;

use App\Models\Contest;
use Illuminate\Database\Seeder;

class ContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contest::factory()->times(10)->create();
    }
}
