<?php

namespace App\Http\Requests\admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RaffleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'ticket_price' => 'required|numeric|min:1',
            'max_participants' => 'required|integer|min:1',
            'closes_at' => 'nullable|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Selected product does not exist.',
            'ticket_price.required' => 'Ticket price is required.',
            'ticket_price.numeric' => 'Ticket price must be a number.',
            'ticket_price.min' => 'Ticket price must be at least 1.',
            'max_participants.required' => 'Max participants is required.',
            'max_participants.integer' => 'Max participants must be an integer.',
            'max_participants.min' => 'Max participants must be at least 1.',
            'closes_at.date' => 'Closes at must be a valid date.',
            'closes_at.after' => 'Closes at must be a future date.',
        ];
    }
}
