<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStepSixRequest extends FormRequest
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
            'first_impression' => 'required|string',
            'about' => 'nullable|string',
            'current_step' => 'required|integer|min:1|max:7',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_impression' => 'primeira impressão',
            'about' => 'sobre'
        ];
    }
}
