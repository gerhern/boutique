<?php

namespace App\Http\Requests\admin;

use App\enums\ProductCondition;
use App\enums\ProductStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'condition'     => [Rule::enum(ProductCondition::class)],
            'status'        => [Rule::enum(ProductStatus::class)],
            'price'         => 'required|numeric|min:0|max:9999.99',
            'category_id'   => 'required|exists:categories,id',
            'images'        => 'required|array|min:1|max:3',
            'images.*'      => 'image|mimes:jpg,jpeg,png|max:2048'
        ];
    }
}
