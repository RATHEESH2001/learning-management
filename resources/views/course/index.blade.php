

@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="container py-4">
    <h1>Courses (Admin)</h1>

    <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">Create new course</a>

    @if($courses->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{ $course->position }}</td>
                    <td>{{ $course->title }}</td>
                    <td>{{ $course->slug }}</td>
                    <td>{{ $course->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                       {{-- <a href="{{ route('courses.showPublic', $course->slug) }}">View</a> --}}
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-secondary">View</a>
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-warning">Edit</a>
 <a href="{{ route('courses.modules.index', $course) }}" class="btn btn-sm btn-info">Modules</a>
                        <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this course?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $courses->links() }}
    @else
        <p>No courses found.</p>
    @endif


</div>

@endsection
