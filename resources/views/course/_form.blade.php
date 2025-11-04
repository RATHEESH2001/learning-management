{{-- Course Form Partial --}}
<div class="card shadow-sm p-4">
    @csrf

    {{-- Title --}}
    <div class="mb-3">
        <label for="title" class="form-label fw-semibold">Course Title</label>
        <input
            type="text"
            name="title"
            id="title"
            value="{{ old('title', $course->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror"
            placeholder="Enter course title"
            required
        >
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Slug --}}
    <div class="mb-3">
        <label for="slug" class="form-label fw-semibold">Slug</label>
        <input
            type="text"
            name="slug"
            id="slug"
            value="{{ old('slug', $course->slug ?? '') }}"
            class="form-control @error('slug') is-invalid @enderror"
            placeholder="auto-generated or custom slug"
        >
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Description --}}
    <div class="mb-3">
        <label for="description" class="form-label fw-semibold">Description</label>
        <textarea
            name="description"
            id="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Write a short summary about the course">{{ old('description', $course->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Price --}}
    <div class="mb-3">
        <label for="price" class="form-label fw-semibold">Price (₹)</label>
        <input
            type="number"
            step="0.01"
            name="price"
            id="price"
            value="{{ old('price', $course->price ?? '') }}"
            class="form-control @error('price') is-invalid @enderror"
            placeholder="Enter course price"
        >
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Published --}}
    <div class="form-check form-switch mb-4">
        <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="is_published"
            name="is_published"
            value="1"
            {{ old('is_published', $course->is_published ?? false) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="is_published">Published</label>
    </div>

    {{-- Submit Button --}}
    <div class="text-end">
        <button type="submit" class="btn btn-primary">
            {{ isset($course) ? 'Update Course' : 'Create Course' }}
            {{-- Save Course --}}
        </button>
    </div>
</div>
