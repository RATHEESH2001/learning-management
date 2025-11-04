<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
class CourseController extends Controller
{
    public function __construct()
    {
        // Protect admin/resource routes; allow public catalog and public show
        $this->middleware('auth')->except(['indexPublic', 'showPublic']);
    }

    /**
     * Admin listing (or general listing if you want).
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $courses = Course::when($q, fn($qry) =>
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
            )
            ->orderBy('position')
            ->paginate(12)
            ->withQueryString();

        return view('course.index', compact('courses', 'q')); // admin view
    }

    /**
     * Public listing (catalog)
     */
    public function indexPublic(Request $request)
    {
        $q = $request->query('q');

        $courses = Course::where('is_published', true)
            ->when($q, fn($qry) =>
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
            )
            ->orderBy('position')
            ->paginate(12)
            ->withQueryString();

        return view('courses.index_public', compact('courses', 'q')); // public view
    }

    /**
     * Show create form
     */
    public function create()
    {
        // $course = new Course();
        return view('course.create');
    }

    /**
     * Store new course
     */
    // public function store(CourseRequest $request)
    // {
    //     $data = $request->validated();

    //     // instructor / owner
    //     $data['user_id'] = auth()->id();

    //     // slug
    //     $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['title']);

    //     // boolean normalization
    //     $data['is_published'] = $data['is_published'] ?? false;

    //     // position: append to end
    //     $max = Course::max('position') ?? 0;
    //     $data['position'] = $max + 1;

    //     // handle thumbnail if provided
    //     if ($request->hasFile('thumbnail')) {
    //         $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
    //     }

    //     $course = Course::create($data);

    //     return redirect()->route('courses.index')->with('success', 'Course created.');
    // }


public function store(CourseRequest $request)
{
    $data = $request->validated();

    // instructor / owner
    $data['user_id'] = auth()->id();

    // slug
    $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug($data['title']);

    // boolean normalization
    $data['is_published'] = $data['is_published'] ?? false;

    // position: append to end (make sure column exists)
    try {
        $max = Course::max('position') ?? 0;
    } catch (Exception $e) {
        Log::error('Error getting max position: '.$e->getMessage());
        $max = 0;
    }
    $data['position'] = $max + 1;

    // handle thumbnail if provided (store first so we don't fail after DB create)
    if ($request->hasFile('thumbnail')) {
        try {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $data['thumbnail'] = $path;
        } catch (Exception $e) {
            Log::error('Thumbnail store failed: '.$e->getMessage());
            // optionally continue without thumbnail
            unset($data['thumbnail']);
        }
    }

    // Log what we are about to insert
    Log::info('Creating course', $data);

    try {
        $course = Course::create($data);

        if (!$course) {
            Log::error('Course::create returned falsy', $data);
            return back()->with('error', 'Failed to create course — check logs.');
        }

        return redirect()->route('courses.index')->with('success', 'Course created.');
    } catch (Exception $e) {
        Log::error('Course create error: '.$e->getMessage(), [
            'exception' => $e,
            'data' => $data,
        ]);
        // helpful during dev: show the error (remove on prod)
        return back()->with('error', 'Error creating course: ' . $e->getMessage())->withInput();
    }
}

    /**
     * Show a course (admin view)
     */
    public function show(Course $course)
    {
        $course->load(['modules' => fn($q) => $q->orderBy('position')]);
        return view('course.show', compact('course'));
    }
 
    /**
     * Public show by slug (route: /courses/{slug})
     */
    public function showPublic($slug)
    {
        $course = Course::with(['modules.lessons'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('courses.show_public', compact('course'));
    }

    /**
     * Edit form
     */
    public function edit(Course $course)
    {
        return view('course.edit', compact('course'));
    }

    /**
     * Update course
     */
    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->validated();

        // slug generation if blank
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $course->id);
        }

        $data['is_published'] = $data['is_published'] ?? false;

        // thumbnail replace
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Course updated.');
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        // remove thumbnail file
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted.');
    }

    /**
     * Generate unique slug for a title
     */
    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Course::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
