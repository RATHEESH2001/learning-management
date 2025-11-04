@extends('layouts.app')

@section('title', 'Lessons')

@section('content')
<div class="container py-4">
    <h1>Lessons for: {{ $module->title }}</h1>
    <a href="{{ route('modules.lessons.create', $module) }}" class="btn btn-primary mb-3">Create Lesson</a>

    @if($lessons->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Title</th>
                    <th>Free</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lessons as $l)
                <tr>
                    <td>{{ $l->position }}</td>
                    <td>{{ $l->title }}</td>
                    <td>{{ $l->is_free ? 'Yes' : 'No' }}</td>
                    <td>{{ $l->is_published ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('lessons.edit', $l) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('lessons.show', $l) }}" class="btn btn-sm btn-secondary">View</a>

                        <form action="{{ route('lessons.destroy', $l) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this lesson?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $lessons->links() }}
    @else
        <p>No lessons found.</p>
    @endif
</div>
@endsection
