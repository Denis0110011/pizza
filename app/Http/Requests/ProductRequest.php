<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:10',
        ];
    }
}
