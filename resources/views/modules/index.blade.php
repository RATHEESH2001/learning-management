@extends('layouts.app')

@section('title', 'Modules')

@section('content')
<div class="container py-4">
    <h1>Modules for {{ $course->title }}</h1>

    <a href="{{ route('courses.modules.create', $course) }}" class="btn btn-primary mb-3">Create Module</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($modules->count())
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
                @foreach($modules as $m)
                <tr>
                    <td>{{ $m->position }}</td>
                    <td>{{ $m->title }}</td>
                    <td>{{ $m->slug }}</td>
                    <td>{{ $m->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('modules.lessons.index', $m) }}" class="btn btn-sm btn-info">Lessons</a>
                        <a href="{{ route('modules.edit', $m) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('modules.destroy', $m) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this module?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $modules->links() }}
    @else
        <p>No modules found.</p>
    @endif
</div>
@endsection
