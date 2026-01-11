<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMunicipalityRequest extends FormRequest
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
            'statcan_id' => ['nullable', 'string', 'max:255', 'unique:municipalities,statcan_id'],
            'name' => ['required', 'string', 'max:255'],
            'name_fr' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:municipalities,slug'],
            'municipality_type_id' => ['required', 'exists:municipality_types,id'],
            'province_id' => ['required', 'exists:provinces,id'],
            'population' => ['nullable', 'integer', 'min:0'],
            'population_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'area_sq_km' => ['nullable', 'numeric', 'min:0'],
            'timezone' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
            'data_source' => ['nullable', 'string', 'max:255'],
        ];
    }
}
