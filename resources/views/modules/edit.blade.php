@extends('layouts.app')

@section('title', 'Edit Module')

@section('content')
<div class="container py-4">
    <h1>Edit module for: {{ $course->title }}</h1>

    <form method="POST" action="{{ route('modules.update', $module) }}">
        @method('PUT')
        @include('modules._form')
    </form>
</div>
@endsection
