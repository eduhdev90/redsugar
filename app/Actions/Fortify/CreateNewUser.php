<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserProfile;
use App\ValueObjects\Gender;
use App\ValueObjects\Interested;
use App\ValueObjects\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        if (!empty($input['name'])) {
            $input['name'] = Str::slug($input['name'], '_', 'pt');
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:50', Rule::unique(User::class)],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'gender' => ['required', new Enum(Gender::class)],
            'interested' => ['required', new Enum(Interested::class)],
            'profile' => ['required', new Enum(Profile::class)],
            'birthday' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d')
            ]
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $profile = UserProfile::create([
            'user_id' => $user->id,
            'gender' => $input['gender'],
            'interested' => $input['interested'],
            'profile' => $input['profile'],
            'birthday' => $input['birthday']
        ]);

        return $user;
    }
}
