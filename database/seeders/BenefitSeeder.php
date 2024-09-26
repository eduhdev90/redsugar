<?php

namespace Database\Seeders;

use App\ValueObjects\Plan\Benefits;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Benefit::create([
            'name' => Benefits::VISITS_LIMIT->value,
            'description' => 'Limite de visitas de perfis',
            'status' => 1
        ]);

        \App\Models\Benefit::create([
            'name' => Benefits::FAVORITES_LIMIT->value,
            'description' => 'Limite de perfis favoritos',
            'status' => 1
        ]);

        \App\Models\Benefit::create([
            'name' => Benefits::MESSAGES_LIMIT->value,
            'description' => 'Limite de chats ativos',
            'status' => 1
        ]);

        \App\Models\Benefit::create([
            'name' => Benefits::ADS_LIMIT->value,
            'description' => 'Limite de anúncios ativos',
            'status' => 1
        ]);
    }
}
