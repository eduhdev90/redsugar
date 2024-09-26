<?php

namespace Database\Factories;

use App\Models\User;
use App\ValueObjects\AcademicBackground;
use App\ValueObjects\ApprovalStatus;
use App\ValueObjects\BeardColor;
use App\ValueObjects\BeardSize;
use App\ValueObjects\Children;
use App\ValueObjects\Drink;
use App\ValueObjects\EyeColor;
use App\ValueObjects\Gender;
use App\ValueObjects\HairColor;
use App\ValueObjects\HairType;
use App\ValueObjects\Interested;
use App\ValueObjects\MaritalStatus;
use App\ValueObjects\MonthlyIncome;
use App\ValueObjects\PersonalPatrimony;
use App\ValueObjects\PhysicalType;
use App\ValueObjects\Profile;
use App\ValueObjects\SkinTone;
use App\ValueObjects\Smoke;
use App\ValueObjects\StyleLife;
use App\ValueObjects\Tattoo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'gender' => fake()->randomElement(Gender::class),
            'interested' => fake()->randomElement(Interested::class),
            'profile' => fake()->randomElement(Profile::class),
            'birthday' => fake()->date('Y-m-d', '-18 years'),
            'country' => 'Brasil',
            'state' => fake()->state(),
            'city' => fake()->city(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'style_life' => fake()->randomElement(StyleLife::class),
            'height' => fake()->randomFloat(2, 1.50, 2.05),
            'physical_type' => fake()->randomElement(PhysicalType::class),
            'skin_tone' => fake()->randomElement(SkinTone::class),
            'eye_color' => fake()->randomElement(EyeColor::class),
            'drink' => fake()->randomElement(Drink::class),
            'smoke' => fake()->randomElement(Smoke::class),
            'hair_color' => fake()->randomElement(HairColor::class),
            'hair_type' => fake()->randomElement(HairType::class),
            'marital_status' => fake()->randomElement(MaritalStatus::class),
            'beard_size' => function (array $attributes) {
                if ($attributes['gender'] !== Gender::MALE) {
                    return null;
                }
                return fake()->randomElement(BeardSize::class);
            },
            'beard_color' => function (array $attributes) {
                if ($attributes['gender'] !== Gender::MALE) {
                    return null;
                }
                return fake()->randomElement(BeardColor::class);
            },
            'children' => fake()->randomElement(Children::class),
            'tattoo' => fake()->randomElement(Tattoo::class),
            'academic_background' => fake()->randomElement(AcademicBackground::class),
            'hobbies' => fake()->text(),
            'occupation' => fake()->words(2, true),
            'monthly_income' => function (array $attributes) {
                if ($attributes['profile'] !== Profile::SUGAR_DADDY_MOMMY) {
                    return null;
                }
                return fake()->randomElement(MonthlyIncome::class);
            },
            'personal_patrimony' => function (array $attributes) {
                if ($attributes['profile'] !== Profile::SUGAR_DADDY_MOMMY) {
                    return null;
                }
                return fake()->randomElement(PersonalPatrimony::class);
            },
            'first_impression' => fake()->sentence(),
            'about' => fake()->text(),
            'status' => ApprovalStatus::APPROVED->value,
        ];
    }
}
