{{-- resources/views/lessons/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Lesson')

@section('content')
<div class="container py-4">
    <h1>Create Lesson for Module: {{ $module->title }}</h1>

    <form action="{{ route('modules.lessons.store', $module) }}" method="POST" enctype="multipart/form-data">
        @csrf

       @include('lessons._form')
    </form>
</div>
@endsection
