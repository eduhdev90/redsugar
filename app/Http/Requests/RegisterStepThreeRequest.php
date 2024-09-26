<?php

namespace App\Http\Requests;

use App\ValueObjects\BeardColor;
use App\ValueObjects\BeardSize;
use App\ValueObjects\Drink;
use App\ValueObjects\EyeColor;
use App\ValueObjects\Gender;
use App\ValueObjects\HairColor;
use App\ValueObjects\HairType;
use App\ValueObjects\MaritalStatus;
use App\ValueObjects\PhysicalType;
use App\ValueObjects\SkinTone;
use App\ValueObjects\Smoke;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterStepThreeRequest extends FormRequest
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
            'height' => 'required|decimal:2',
            'physical_type' => ['required', new Enum(PhysicalType::class)],
            'skin_tone' => ['required', new Enum(SkinTone::class)],
            'eye_color' => ['required', new Enum(EyeColor::class)],
            'drink' => ['required', new Enum(Drink::class)],
            'smoke' => ['required', new Enum(Smoke::class)],
            'hair_color' => ['required', new Enum(HairColor::class)],
            'hair_type' => ['required', new Enum(HairType::class)],
            'marital_status' => ['nullable', new Enum(MaritalStatus::class)],
            'current_step' => 'required|integer|min:1|max:7',
        ];

        if ($this->user()?->profile?->gender === Gender::MALE->value) {
            $validationData = array_merge($validationData, [
                'beard_size' => ['required', new Enum(BeardSize::class)],
                'beard_color' => ['nullable', new Enum(BeardColor::class)]
            ]);
        }

        return $validationData;
    }

    public function attributes(): array
    {
        return [
            'height' => 'altura',
            'physical_type' => 'tipo físico',
            'skin_tone' => 'tom de pele',
            'eye_color' => 'cor dos olhos',
            'drink' => 'você bebe?',
            'smoke' => 'você fuma?',
            'hair_color' => 'cor do cabelo',
            'hair_type' => 'tipo de cabelo',
            'marital_status' => 'estado civil',
            'beard_size' => 'tamanho da barba',
            'beard_color' => 'cor da barba'
        ];
    }
}
