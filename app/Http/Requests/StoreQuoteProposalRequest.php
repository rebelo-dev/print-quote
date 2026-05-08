<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteProposalRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quote_request_id' => 'required|exists:quote_requests,id',
            'material_id'      => 'required|exists:materials,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'estimated_hours'  => 'nullable|numeric|min:0',
            'estimated_weight' => 'nullable|numeric|min:0',
            'quantity'         => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'notes'            => 'nullable|string',
        ];
    }
}
