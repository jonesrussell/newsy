<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:500', 'unique:news_sources,url'],
            'type' => ['required', Rule::in(['newspaper', 'tv', 'radio', 'online', 'blog', 'aggregator'])],
            'scope' => ['required', Rule::in(['hyperlocal', 'local', 'regional', 'provincial', 'national'])],
            'language' => ['required', Rule::in(['en', 'fr', 'bilingual', 'other'])],
            'reliability_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'url.unique' => 'This news source URL is already registered in the system.',
            'reliability_score.max' => 'The reliability score must not exceed 100.',
        ];
    }
}
