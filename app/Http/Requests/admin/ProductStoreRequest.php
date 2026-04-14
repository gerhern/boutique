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
            'condition'     => [Rule::enum(ProductCondition::class),'required'],
            'status'        => [Rule::enum(ProductStatus::class)],
            'price'         => 'required|numeric|min:1|max:9999.99',
            'category_id'   => 'required|exists:categories,id',
            'images'        => 'required|array|min:1|max:3',
            'images.*'      => 'image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'name.string' => 'The product name must be a string.',
            'name.max' => 'The product name cannot exceed 255 characters.',
            'description.string' => 'The product description must be a string.',
            'description.max' => 'The product description cannot exceed 1000 characters.',
            'condition.required' => 'The product condition is required.',
            'condition.enum' => 'The selected condition is invalid.',
            'status.enum' => 'The selected status is invalid.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 1.',
            'price.max' => 'The price cannot exceed 9999.99.',
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'images.required' => 'At least one image is required.',
            'images.array' => 'Images must be an array.',
            'images.min' => 'At least one image is required.',
            'images.max' => 'A maximum of 3 images are allowed.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Each image must be a file of type: jpg, jpeg, png.',
            'images.*.max' => 'Each image must not exceed 2MB in size.'
        ];
    }
}
