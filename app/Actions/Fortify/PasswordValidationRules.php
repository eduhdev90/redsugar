<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function passwordRules(): array
    {
        $password = new Password;
        $password->requireNumeric();
        $password->requireUppercase();
        $password->requireSpecialCharacter();

        return ['required', 'string', $password, 'confirmed'];
    }
}
