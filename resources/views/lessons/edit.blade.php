{{-- resources/views/lessons/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Lesson')

@section('content')
<form action="{{ route('lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @include('lessons._form')
</form>
@endsection
