<?php

namespace Database\Seeders;

use App\Models\Enums\EnumFileType;
use Illuminate\Database\Seeder;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EnumFileType::factory(10)->create();
    }
}
