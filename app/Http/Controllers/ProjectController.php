<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();

        return view('projects.index', [
            'projects' => $projects,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:100',
        ]);

        $project = Project::create($request->only([
            'name',
            'description',
            'location',
            'start_date',
            'end_date',
            'budget',
            'status',
        ]));

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project,
        ], 201);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|string|max:100',
        ]);

        $project->update($request->only([
            'name',
            'description',
            'location',
            'start_date',
            'end_date',
            'budget',
            'status',
        ]));

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project,
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully',
        ]);
    }
}
