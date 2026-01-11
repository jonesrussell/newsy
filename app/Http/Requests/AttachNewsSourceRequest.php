<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachNewsSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'news_source_id' => ['required', 'exists:news_sources,id'],
            'coverage_type' => ['required', Rule::in(['primary', 'secondary', 'occasional'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'news_source_id.exists' => 'The selected news source does not exist.',
        ];
    }
}
