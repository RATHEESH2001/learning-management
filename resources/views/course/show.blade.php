@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="container py-4">
    <h1>{{ $course->title }}</h1>

    <p>{{ $course->short_description }}</p>

    <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to list</a>
</div>
@endsection
