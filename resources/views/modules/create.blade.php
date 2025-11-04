@extends('layouts.app')

@section('title', 'Create Module')

@section('content')
<div class="container py-4">
    <h1>Create module for: {{ $course->title }}</h1>

    <form method="POST" action="{{ route('courses.modules.store', $course) }}">
        @include('modules._form')
    </form>
</div>
@endsection
