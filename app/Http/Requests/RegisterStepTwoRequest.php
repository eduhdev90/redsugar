<?php

namespace App\Http\Requests;

use App\ValueObjects\StyleLife;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterStepTwoRequest extends FormRequest
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
            'style_life' => ['required', new Enum(StyleLife::class)],
            'current_step' => 'required|integer|min:1|max:7',
        ];
    }

    public function attributes(): array
    {
        return [
            'style_life' => 'tipo de relacionamento'
        ];
    }
}
