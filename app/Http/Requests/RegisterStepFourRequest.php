<?php

namespace App\Http\Requests;

use App\ValueObjects\AcademicBackground;
use App\ValueObjects\Children;
use App\ValueObjects\MonthlyIncome;
use App\ValueObjects\PersonalPatrimony;
use App\ValueObjects\Profile;
use App\ValueObjects\Tattoo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterStepFourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $validationData = [
            'children' => ['required', new Enum(Children::class)],
            'tattoo' => [new Enum(Tattoo::class)],
            'academic_background' => [new Enum(AcademicBackground::class)],
            'hobbies' => 'nullable|string',
            'occupation' => 'nullable|string',
            'current_step' => 'required|integer|min:1|max:7',
        ];

        if ($this->user()?->profile?->profile === Profile::SUGAR_DADDY_MOMMY->value) {
            $validationData = array_merge($validationData, [
                'monthly_income' => ['required', new Enum(MonthlyIncome::class)],
                'personal_patrimony' => ['required', new Enum(PersonalPatrimony::class)]
            ]);
        }

        return $validationData;
    }

    public function attributes(): array
    {
        return [
            'children' => 'filhos',
            'tattoo' => 'tatuagem',
            'academic_background' => 'formação acadêmica',
            'occupation' => 'profissão',
            'monthly_income' => 'renda mensal',
            'personal_patrimony' => 'patrimônio'
        ];
    }
}
