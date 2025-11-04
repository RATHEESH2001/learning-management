<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModuleRequest extends FormRequest
{
    public function authorize()
    {
        // adjust auth logic as needed (e.g., only course owners/admins)
        return auth()->check();
    }

    public function rules()
    {
        $moduleId = $this->route('module')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable','string','max:255',
                Rule::unique('modules')->ignore($moduleId),
            ],
            'summary' => ['nullable','string'],
            'position' => ['nullable','integer','min:0'],
            'is_published' => ['sometimes','boolean'],
            'published_at' => ['nullable','date'],
        ];
    }

    protected function prepareForValidation()
    {
        // coerce checkbox value
        if ($this->has('is_published')) {
            $this->merge(['is_published' => (bool) $this->input('is_published')]);
        }
    }
}
