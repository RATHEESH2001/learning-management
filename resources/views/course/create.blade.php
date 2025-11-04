@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
<div class="container py-4">
    <h1>Create Course</h1>

    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
@include('course._form')
        {{-- <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" value="{{ old('title') }}" class="form-control" required>
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Slug (optional)</label>
            <input name="slug" value="{{ old('slug') }}" class="form-control">
            @error('slug') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Short description</label>
            <textarea name="short_description" class="form-control">{{ old('short_description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <button class="btn btn-primary">Save</button> --}}
    </form>
</div>
@endsection
