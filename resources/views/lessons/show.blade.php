{{-- resources/views/lessons/show.blade.php --}}
@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary mb-3">Back</a>

    <h1>{{ $lesson->title }}</h1>

    <p class="text-muted mb-3">
        Module:
        @if($lesson->module)
            <a href="{{ route('modules.lessons.index', $lesson->module) }}">{{ $lesson->module->title }}</a>
        @else
            N/A
        @endif
        • Position: {{ $lesson->position ?? '—' }}
        @if($lesson->is_free) • <span class="badge bg-success">Free</span> @endif
        @if($lesson->is_published) • Published @endif
    </p>

    {{-- Video (media) --}}
    @if($lesson->getFirstMediaUrl('videos'))
        <div class="mb-4">
            <video controls style="max-width:100%">
                <source src="{{ $lesson->getFirstMediaUrl('videos') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    @elseif($lesson->video_url)
        <div class="mb-4">
            <div class="ratio ratio-16x9">
                <iframe src="{{ $lesson->video_url }}" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    @endif

    {{-- Content rendered from Markdown accessor --}}
    <div class="lesson-content mb-4">
        {!! $lesson->content_html ?? nl2br(e($lesson->content_markdown ?? '')) !!}
    </div>

    {{-- Attachments --}}
    @if($lesson->getMedia('attachments')->count())
        <h5>Attachments</h5>
        <ul>
            @foreach($lesson->getMedia('attachments') as $att)
                <li><a href="{{ $att->getUrl() }}" target="_blank">{{ $att->file_name }}</a></li>
            @endforeach
        </ul>
    @endif

    {{-- Images --}}
    @if($lesson->getMedia('images')->count())
        <h5>Images</h5>
        <div class="d-flex gap-2 flex-wrap mb-4">
            @foreach($lesson->getMedia('images') as $img)
                <div style="max-width:200px">
                    <img src="{{ $img->getUrl() }}" alt="{{ $img->file_name }}" class="img-fluid rounded">
                </div>
            @endforeach
        </div>
    @endif

    @can('update', $lesson)
        <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-warning">Edit</a>
    @endcan

</div>
@endsection
