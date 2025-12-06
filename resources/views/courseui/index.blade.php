@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Courses</h1>

    <form method="GET" action="{{ route('courses.indexPublic') }}" class="mb-3">
        <div class="input-group">
            <input type="search" name="q" class="form-control" placeholder="Search courses..." value="{{ old('q', $q) }}">
            <button class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    @if($courses->count())
        <div class="row g-3">
            @foreach($courses as $course)
                <div class="col-md-4">
                    <div class="card h-100">
                        @if($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" class="card-img-top" alt="{{ $course->title }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text text-truncate">{{ Str::limit(strip_tags($course->description), 120) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('courses.showPublic', $course->slug) }}" class="btn btn-primary btn-sm">View course</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    @else
        <p>No courses found.</p>
    @endif
</div>
@endsection
