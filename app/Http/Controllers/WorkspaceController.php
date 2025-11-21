<?php

/**
 * Probabilitas Pro - Dynamic Expert System
 * Workspace Controller
 * 
 * @author    Ahda Firly Barori
 * @copyright 2025 Ahda Firly Barori
 * @license   Proprietary
 */

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Attribute;
use App\Models\ClassModel;
use App\Models\TrainingData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkspaceController extends Controller
{
    public function show(Project $project)
    {
        // Touch project to update 'updated_at' for recent tracking
        $project->touch();
        
        $project->load(['attributes', 'classes']);
        
        $trainingData = TrainingData::where('project_id', $project->id)
            ->get()
            ->mapWithKeys(function ($item) {
                return ["{$item->class_id}_{$item->attribute_id}" => $item->is_associated];
            });

        return Inertia::render('Workspace', [
            'project' => $project,
            'trainingData' => $trainingData
        ]);
    }

    public function updateAttribute(Request $request, Project $project, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $attribute->update($validated);

        return response()->json(['success' => true]);
    }

    public function updateClass(Request $request, Project $project, ClassModel $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $class->update($validated);

        return response()->json(['success' => true]);
    }

    public function addAttribute(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $count = $project->attributes()->count() + 1;
        
        $attribute = Attribute::create([
            'project_id' => $project->id,
            'code' => 'X' . $count,
            'name' => $validated['name'],
        ]);

        // Auto-create training data entries for this attribute with all existing classes
        $classes = $project->classes;
        foreach ($classes as $class) {
            TrainingData::create([
                'project_id' => $project->id,
                'class_id' => $class->id,
                'attribute_id' => $attribute->id,
                'is_associated' => false // Default: not associated
            ]);
        }

        return response()->json([
            'attribute' => $attribute,
            'message' => 'Atribut berhasil ditambahkan dan matrix table telah diupdate'
        ]);
    }

    public function addClass(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $count = $project->classes()->count() + 1;
        
        $class = ClassModel::create([
            'project_id' => $project->id,
            'code' => 'C' . $count, // Fixed: Changed from 'Y' to 'C'
            'name' => $validated['name'],
        ]);

        // Auto-create training data entries for this class with all existing attributes
        $attributes = $project->attributes;
        foreach ($attributes as $attribute) {
            TrainingData::create([
                'project_id' => $project->id,
                'class_id' => $class->id,
                'attribute_id' => $attribute->id,
                'is_associated' => false // Default: not associated
            ]);
        }

        return response()->json([
            'class' => $class,
            'message' => 'Kelas berhasil ditambahkan dan matrix table telah diupdate'
        ]);
    }

    public function updateTrainingData(Request $request, Project $project)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'attribute_id' => 'required|exists:attributes,id',
            'is_associated' => 'required|boolean',
        ]);

        TrainingData::updateOrCreate(
            [
                'project_id' => $project->id,
                'class_id' => $validated['class_id'],
                'attribute_id' => $validated['attribute_id'],
            ],
            [
                'is_associated' => $validated['is_associated']
            ]
        );

        return response()->json(['success' => true]);
    }
}

