<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //public
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'quantity'       => 'integer|min:1',
            'slug'           => 'required|string|exists:users,slug',
        ];
    }
}
