<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class AdminProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|min:3|max:100',
            'description' => 'nullable|string|min:3|max:500',
            'price' => 'sometimes|numeric|min:1',
            'type' => 'sometimes|in:pizza,drink',
        ];
    }
}
