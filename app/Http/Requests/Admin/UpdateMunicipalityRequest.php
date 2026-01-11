<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMunicipalityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'statcan_id' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('municipalities')->ignore($this->route('municipality'))],
            'name' => ['sometimes', 'string', 'max:255'],
            'name_fr' => ['sometimes', 'nullable', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('municipalities')->ignore($this->route('municipality'))],
            'municipality_type_id' => ['sometimes', 'exists:municipality_types,id'],
            'province_id' => ['sometimes', 'exists:provinces,id'],
            'population' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'population_year' => ['sometimes', 'nullable', 'integer', 'min:1900', 'max:2100'],
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            'area_sq_km' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'timezone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'metadata' => ['sometimes', 'nullable', 'array'],
            'data_source' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
