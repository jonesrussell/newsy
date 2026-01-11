<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'url' => ['sometimes', 'url', 'max:500', Rule::unique('news_sources')->ignore($this->route('news_source'))],
            'type' => ['sometimes', Rule::in(['newspaper', 'tv', 'radio', 'online', 'blog', 'aggregator'])],
            'scope' => ['sometimes', Rule::in(['hyperlocal', 'local', 'regional', 'provincial', 'national'])],
            'language' => ['sometimes', Rule::in(['en', 'fr', 'bilingual', 'other'])],
            'reliability_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
