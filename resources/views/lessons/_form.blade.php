@csrf

<div class="mb-3">
    <label class="form-label" for="title">Title</label>
    <input id="title" name="title" value="{{ old('title', $lesson->title) }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label" for="slug">Slug (optional)</label>
    <input id="slug" name="slug" value="{{ old('slug', $lesson->slug) }}" class="form-control">
    <small class="form-text text-muted">Leave blank to auto-generate from title</small>
</div>

<div class="mb-3">
    <label class="form-label" for="content_markdown">Content (Markdown)</label>
    <textarea id="content_markdown" name="content_markdown" class="form-control" rows="8">{{ old('content_markdown', $lesson->content_markdown) }}</textarea>
    <small class="form-text text-muted">You can write Markdown. Preview will be rendered using CommonMark.</small>
</div>

<div class="mb-3">
    <label class="form-label" for="video">Upload Video (optional)</label>
    <input id="video" name="video" type="file" accept="video/*" class="form-control">
    @if($lesson->getFirstMediaUrl('videos'))
        <div class="mt-2">
            <video width="320" height="180" controls>
                <source src="{{ $lesson->getFirstMediaUrl('videos') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <p class="small text-muted">Existing video shown above. Uploading a new file will replace it.</p>
        </div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label" for="video_url">Video URL (optional)</label>
    <input id="video_url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="form-control" placeholder="https://...">
</div>

<div class="mb-3">
    <label class="form-label" for="duration_seconds">Duration (seconds)</label>
    <input id="duration_seconds" name="duration_seconds" value="{{ old('duration_seconds', $lesson->duration_seconds) }}" class="form-control" type="number" min="0">
</div>

<div class="mb-3">
    <label class="form-label" for="attachments">Attachments (multiple files)</label>
    <input id="attachments" name="attachments[]" type="file" multiple class="form-control">
    @if($lesson->getMedia('attachments')->count())
        <ul class="mt-2">
            @foreach($lesson->getMedia('attachments') as $att)
                <li>
                    <a href="{{ $att->getUrl() }}" target="_blank">{{ $att->file_name }}</a>
                    <form method="POST" action="{{ route('lessons.media.destroy', [$lesson, $att->id]) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-link text-danger" onclick="return confirm('Remove attachment?')">Remove</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<div class="mb-3">
    <label class="form-label" for="images">Images (multiple)</label>
    <input id="images" name="images[]" type="file" multiple accept="image/*" class="form-control">
    @if($lesson->getMedia('images')->count())
        <div class="mt-2 d-flex gap-2">
            @foreach($lesson->getMedia('images') as $img)
                <div style="max-width:120px">
                    <img src="{{ $img->getUrl() }}" alt="{{ $img->file_name }}" class="img-fluid rounded">
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label for="position" class="form-label">Position</label>
        <input id="position" name="position" value="{{ old('position', $lesson->position ?? 0) }}" class="form-control" type="number" min="0">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="is_free" name="is_free" {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_free">Free</label>
        </div>
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published" {{ old('is_published', $lesson->is_published) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Published</label>
        </div>
    </div>

    <div class="col-md-3">
        <label for="published_at" class="form-label">Publish at</label>
        <input id="published_at" name="published_at" value="{{ old('published_at', optional($lesson->published_at)->format('Y-m-d\TH:i') ) }}" class="form-control" type="datetime-local">
    </div>
</div>

<button type="submit" class="btn btn-primary">
    {{ isset($lesson) && $lesson->exists ? 'Update Lesson' : 'Create Lesson' }}
</button>
