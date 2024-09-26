<?php

namespace Database\Factories;

use App\Models\UserPhoto;
use App\Models\UserProfile;
use App\ValueObjects\ApprovalStatus;
use App\ValueObjects\PhotoVisibility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPhoto>
 */
class UserPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_profile_id' => UserProfile::factory(),
            'path' => function (array $attributes) {
                $filepath = storage_path('app/public/photos/' . $attributes['user_profile_id']);
                if (!File::exists($filepath)) {
                    File::makeDirectory($filepath, recursive: true);
                }
                $photo = fake()->image($filepath, 1080, 1350, null, false);
                return 'photos/' . $attributes['user_profile_id'] . '/' . $photo;
            },
            'visibility' => PhotoVisibility::PUBLIC->value,
            'status' => ApprovalStatus::APPROVED->value
        ];
    }



    public function configure(): static
    {
        return $this->afterMaking(function (UserPhoto $userPhoto) {
            // ...
        })->afterCreating(function (UserPhoto $userPhoto) {
            $userProfile = UserProfile::where('id', '=', $userPhoto->user_profile_id)->first();
            $userProfile->profile_photo_id = $userPhoto->id;
            $userProfile->profile_image = $userPhoto->path;
            $userProfile->save();
        });
    }
}
