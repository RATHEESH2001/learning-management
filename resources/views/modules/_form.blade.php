@csrf

<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input id="title" name="title" value="{{ old('title', $module->title) }}" class="form-control" required>
</div>

<div class="mb-3">
    <label for="slug" class="form-label">Slug (optional)</label>
    <input id="slug" name="slug" value="{{ old('slug', $module->slug) }}" class="form-control">
    <small class="text-muted">Leave blank to auto-generate</small>
</div>

<div class="mb-3">
    <label for="summary" class="form-label">Summary</label>
    <textarea id="summary" name="summary" class="form-control" rows="4">{{ old('summary', $module->summary) }}</textarea>
</div>

<div class="mb-3 row">
    <div class="col-md-3">
        <label for="position" class="form-label">Position</label>
        <input id="position" name="position" value="{{ old('position', $module->position ?? 0) }}" class="form-control" type="number" min="0">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published" {{ old('is_published', $module->is_published) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Published</label>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    {{ isset($module) && $module->exists ? 'Update Module' : 'Create Module' }}
</button>
