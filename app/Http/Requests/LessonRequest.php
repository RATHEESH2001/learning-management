<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // adjust to policy if needed
    }

    public function rules(): array
    {
        $lessonId = $this->route('lesson')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('lessons')->ignore($lessonId)],
            'content_markdown' => ['nullable', 'string'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv|max:51200'], // max 50MB
            'video_url' => ['nullable', 'url'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_free' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // each attachment max 10MB
            'images.*' => ['nullable', 'image', 'max:5120'], // images max 5MB
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_free' => $this->has('is_free') ? (bool) $this->input('is_free') : false,
            'is_published' => $this->has('is_published') ? (bool) $this->input('is_published') : false,
        ]);
    }
}
