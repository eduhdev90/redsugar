<?php

namespace App\Http\Requests;

use App\Models\User;
use App\ValueObjects\AcademicBackground;
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
use App\ValueObjects\SkinTone;
use App\ValueObjects\Smoke;
use App\ValueObjects\StyleLife;
use App\ValueObjects\Tattoo;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Str;

class UpdateUserProfileRequest extends FormRequest
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
        return [
            // 'name' => ['sometimes', 'required', 'string', 'max:50', Rule::unique(User::class)->ignore($this->user()->id)],
            'gender' => ['sometimes', 'required', new Enum(Gender::class)],
            'interested' => ['sometimes', 'required', new Enum(Interested::class)],
            'birthday' => [
                'sometimes',
                'required',
                'date_format:Y-m-d',
                'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d')
            ],
            'country' => 'required_with:state,city,latitude,longitude',
            'state' => 'required_with:country,city,latitude,longitude',
            'city' => 'required_with:country,state,latitude,longitude',
            'latitude' => 'required_with:country,state,city|numeric|decimal:5,8',
            'longitude' => 'required_with:country,state,city|numeric|decimal:5,8',
            'style_life' => ['sometimes', 'required', new Enum(StyleLife::class)],
            'height' => 'sometimes|required',
            'physical_type' => ['sometimes', 'required', new Enum(PhysicalType::class)],
            'skin_tone' => ['sometimes', 'required', new Enum(SkinTone::class)],
            'eye_color' => ['sometimes', 'required', new Enum(EyeColor::class)],
            'drink' => ['sometimes', 'required', new Enum(Drink::class)],
            'smoke' => ['sometimes', 'required', new Enum(Smoke::class)],
            'hair_color' => ['sometimes', 'required', new Enum(HairColor::class)],
            'hair_type' => ['sometimes', 'required', new Enum(HairType::class)],
            'marital_status' => ['nullable', new Enum(MaritalStatus::class)],
            'beard_size' => ['sometimes', 'required', new Enum(BeardSize::class)],
            'beard_color' => ['nullable', new Enum(BeardColor::class)],
            'children' => ['sometimes', 'required', new Enum(Children::class)],
            'tattoo' => ['nullable', new Enum(Tattoo::class)],
            'academic_background' => ['nullable', new Enum(AcademicBackground::class)],
            'hobbies' => 'nullable|string',
            'online' => 'nullable|numeric',
            'occupation' => 'nullable|string',
            'monthly_income' => ['sometimes', 'required', new Enum(MonthlyIncome::class)],
            'personal_patrimony' => ['sometimes', 'required', new Enum(PersonalPatrimony::class)],
            'first_impression' => 'sometimes|required|string',
            'about' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'style_life' => 'tipo de relacionamento',
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
            'beard_color' => 'cor da barba',
            'children' => 'filhos',
            'tattoo' => 'tatuagem',
            'academic_background' => 'formação acadêmica',
            'occupation' => 'profissão',
            'monthly_income' => 'renda mensal',
            'personal_patrimony' => 'patrimônio',
            'first_impression' => 'primeira impressão',
            'about' => 'sobre'
        ];
    }

    // protected function prepareForValidation(): void
    // {
    //     $this->merge([
    //         'name' => Str::slug($this->name, '_', 'pt'),
    //     ]);
    // }
}
