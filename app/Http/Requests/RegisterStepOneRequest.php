<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStepOneRequest extends FormRequest
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
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'latitude' => 'required|numeric|decimal:5,8',
            'longitude' => 'required|numeric|decimal:5,8',
            'current_step' => 'required|integer|min:1|max:7',
        ];
    }
}
