@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
<div class="container py-4">
    <h1>Edit course</h1>

    <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
@include('course._form')

        {{-- <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" value="{{ old('title', $course->title) }}" class="form-control" required>
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input name="slug" value="{{ old('slug', $course->slug) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Short description</label>
            <textarea name="short_description" class="form-control">{{ old('short_description', $course->short_description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            @if($course->thumbnail)
                <div class="mb-2"><img src="{{ asset('storage/'.$course->thumbnail) }}" style="max-width:200px"></div>
            @endif
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button> --}}
    </form>
</div>
@endsection
