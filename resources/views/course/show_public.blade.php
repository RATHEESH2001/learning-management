@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="container py-4">
    <h1>Courses</h1>

    @if($courses->count())
        <div class="row">
            @foreach($courses as $course)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text">{{ Str::limit($course->short_description ?? $course->description, 120) }}</p>
                        <a href="{{ route('courses.showPublic', $course->slug) }}" class="btn btn-primary">View course</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{ $courses->links() }}
    @else
        <p>No courses available right now.</p>
    @endif
</div>
@endsection
