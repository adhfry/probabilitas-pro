<?php

/**
 * Probabilitas Pro - Dynamic Expert System
 * Project Controller
 * 
 * @author    Ahda Firly Barori
 * @copyright 2025 Ahda Firly Barori
 * @license   Proprietary
 */

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Attribute;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount(['attributes', 'classes'])
            ->latest()
            ->get();

        return Inertia::render('Dashboard', [
            'projects' => $projects
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'x_label' => 'required|string|max:255',
            'y_label' => 'required|string|max:255',
            'x_count' => 'required|integer|min:1',
            'y_count' => 'required|integer|min:1',
        ]);

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'x_label' => $validated['x_label'],
            'y_label' => $validated['y_label'],
        ]);

        // Generate initial attributes (X variables)
        for ($i = 1; $i <= $validated['x_count']; $i++) {
            Attribute::create([
                'project_id' => $project->id,
                'code' => 'X' . $i,
                'name' => $validated['x_label'] . ' ' . $i,
            ]);
        }

        // Generate initial classes (Y variables)
        for ($i = 1; $i <= $validated['y_count']; $i++) {
            ClassModel::create([
                'project_id' => $project->id,
                'code' => 'Y' . $i,
                'name' => $validated['y_label'] . ' ' . $i,
            ]);
        }

        return redirect()->route('workspace.show', $project->id);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('dashboard');
    }
}
