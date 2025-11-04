<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // restrict to authenticated users (or add role checks as needed)
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // If route has {course} parameter, use its id for unique rule
        $courseId = $this->route('course') ? $this->route('course')->id : null;

        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug,' . ($courseId ?? 'NULL'),
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'is_published' => 'sometimes|boolean',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
