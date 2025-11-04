<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // index: list lessons for a module
    public function index(Module $module)
    {
        $lessons = $module->lessons()->paginate(20);
        return view('lessons.index', compact('module', 'lessons'));
    }

    // create form
    public function create(Module $module)
    {
        $lesson = new Lesson();
        return view('lessons.create', compact('module', 'lesson'));
    }

    // store new lesson
    public function store(LessonRequest $request, Module $module)
    {
        $data = $request->validated();

        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
            $count = Lesson::where('slug', 'like', $data['slug'].'%')->count();
            if ($count) $data['slug'] .= '-'.($count + 1);
        }

        $data['module_id'] = $module->id;

        // create lesson
        $lesson = Lesson::create($data);

        // handle video file upload (Spatie medialibrary)
        if ($request->hasFile('video')) {
            $lesson->addMediaFromRequest('video')->toMediaCollection('videos');
            // optionally set video_path to media url or disk path
            $lesson->video_path = $lesson->getFirstMediaUrl('videos') ?: null;
        }

        // handle attachments (multiple)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $lesson->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // handle images (multiple)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $lesson->addMedia($img)->toMediaCollection('images');
            }
        }

        // save any changes (e.g., video_path)
        $lesson->save();

        return redirect()->route('modules.lessons.index', $module)
                         ->with('success', 'Lesson created.');
    }

    // edit form (shallow)
    public function edit(Lesson $lesson)
    {
        $module = $lesson->module;
        return view('lessons.edit', compact('module', 'lesson'));
    }

    // update lesson
    public function update(LessonRequest $request, Lesson $lesson)
    {
        $data = $request->validated();

        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
            $count = Lesson::where('slug', 'like', $data['slug'].'%')->where('id', '!=', $lesson->id)->count();
            if ($count) $data['slug'] .= '-'.($count + 1);
        }

        $lesson->update($data);

        // replace video if provided
        if ($request->hasFile('video')) {
            // remove previous video if any
            $lesson->clearMediaCollection('videos');
            $lesson->addMediaFromRequest('video')->toMediaCollection('videos');
            $lesson->video_path = $lesson->getFirstMediaUrl('videos') ?: null;
        }

        // add new attachments (keep existing)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $lesson->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // add images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $lesson->addMedia($img)->toMediaCollection('images');
            }
        }

        $lesson->save();

        return redirect()->route('modules.lessons.index', $lesson->module)
                         ->with('success', 'Lesson updated.');
    }

    // destroy
    public function destroy(Lesson $lesson)
    {
        $module = $lesson->module;
        $lesson->delete(); // media removed if configured with cascade
        return redirect()->route('modules.lessons.index', $module)
                         ->with('success', 'Lesson deleted.');
    }

    // optional show
    // public function show(Module $module, Lesson $lesson)
    // {
    //     if ($lesson->module_id !== $module->id) abort(404);
    //     return view('lessons.show', compact('module', 'lesson'));
    // }
    // app/Http/Controllers/LessonController.php

// replace the existing show method with this:
public function show(\App\Models\Lesson $lesson)
{
    // eager-load module and media to avoid extra queries in the view
    $lesson->load('module');

    return view('lessons.show', compact('lesson'));
}


    // Example endpoint to remove an attachment by media id
    public function removeAttachment(Lesson $lesson, $mediaId)
    {
        $media = $lesson->media()->find($mediaId);
        if ($media) $media->delete();
        return back()->with('success', 'Attachment removed.');
    }
}
