<?php

namespace Database\Seeders;

use App\ValueObjects\Plan\ProductType;
use App\ValueObjects\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'Basic',
            'description' => 'Plano Gratuito',
            'profile' => Profile::SUGAR_DADDY_MOMMY->value,
            'type_plan' => ProductType::FREE->value,
            'status' => 1
        ]);

        \App\Models\Product::create([
            'name' => 'Basic',
            'description' => 'Plano Gratuito',
            'profile' => Profile::SUGAR_BABY->value,
            'type_plan' => ProductType::FREE->value,
            'status' => 1
        ]);
    }
}
