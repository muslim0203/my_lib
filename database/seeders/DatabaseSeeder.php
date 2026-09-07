<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            EducationTypeSeeder::class,
            ProcessStepSeeder::class,
            AcademicDegreeSeeder::class,
            AcademicPositionSeeder::class,
            ProductGenresSeeder::class,
            ProductPriceTypeSeeder::class,
            ProductStatusSeeder::class,
            ProductTagsSeeder::class,
            ProductTypesSeeder::class,
            CategorySeeder::class,
            RequestTypeSeeder::class,
            ReportTypeSeeder::class,
            ActivitySphereSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
