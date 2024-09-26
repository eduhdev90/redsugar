<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Backoffice API',
            'email' => 'backoffice@redesugar.com'
        ]);

        \App\Models\UserPhoto::factory(env('TOTAL_USER_SEEDER', 10))
            ->create();

        $this->call([
            BenefitSeeder::class,
            ProductSeeder::class,
            SubscriptionSeeder::class,
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // \App\Models\User::factory(1)->create(
        //     [
        //         'name' => 'luke',
        //         'email' => 'luke@jedi.com',
        //         'email_verified_at' => null,
        //     ]
        // );
    }
}
