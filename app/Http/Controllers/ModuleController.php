<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // adjust as needed
    }

    // List modules for a course (admin)
    public function index(Course $course)
    {
        $modules = $course->modules()->paginate(20);
        return view('modules.index', compact('course', 'modules'));
    }

    // Show form to create module for a course
    public function create(Course $course)
    {
        $module = new Module();
        return view('modules.create', compact('course', 'module'));
    }

    // Store new module
    public function store(ModuleRequest $request, Course $course)
    {
        $data = $request->validated();

        // if no slug provided, generate from title
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
            // ensure unique
            $count = Module::where('slug', 'like', $data['slug'].'%')->count();
            if ($count) $data['slug'] .= '-'.($count + 1);
        }

        $data['course_id'] = $course->id;
        $module = Module::create($data);

        return redirect()->route('courses.modules.index', $course)
                         ->with('success', 'Module created.');
    }

    // Show edit form
    public function edit(Module $module)
    {
        // Because routes are shallow, we don't always have course param — load it
        $course = $module->course;
        return view('modules.edit', compact('module', 'course'));
    }

    // Update module
    public function update(ModuleRequest $request, Module $module)
    {
        $data = $request->validated();

        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
            $count = Module::where('slug', 'like', $data['slug'].'%')->where('id', '!=', $module->id)->count();
            if ($count) $data['slug'] .= '-'.($count + 1);
        }

        $module->update($data);

        return redirect()->route('courses.modules.index', $module->course)
                         ->with('success', 'Module updated.');
    }

    // Delete
    public function destroy(Module $module)
    {
        $course = $module->course;
        $module->delete();

        return redirect()->route('courses.modules.index', $course)
                         ->with('success', 'Module deleted.');
    }

    // Optional: show public module page
    public function show(Course $course, Module $module)
    {
        // ensure belongs to course if nested route used
        if ($module->course_id !== $course->id) {
            abort(404);
        }

        return view('modules.show', compact('course', 'module'));
    }
}
