<?php

/**
 * Probabilitas Pro - Dynamic Expert System
 * Analysis Controller - Naive Bayes Implementation
 * 
 * @author    Ahda Firly Barori
 * @copyright 2025 Ahda Firly Barori
 * @license   Proprietary
 */

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TrainingData;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function analyze(Request $request, Project $project)
    {
        $validated = $request->validate([
            'selected_attributes' => 'required|array',
            'selected_attributes.*' => 'exists:attributes,id',
        ]);

        $selectedAttributes = $validated['selected_attributes'];
        $classes = $project->classes;
        $results = [];
        $calculationSteps = [];

        foreach ($classes as $class) {
            $score = 1.0;
            $classSteps = [
                'class_name' => $class->name,
                'class_code' => $class->code,
                'prior' => $class->prior_probability > 0 ? $class->prior_probability : (1 / $classes->count()),
                'likelihoods' => []
            ];

            // Apply prior probability or uniform distribution
            $prior = $class->prior_probability > 0 ? $class->prior_probability : (1 / $classes->count());
            $score *= $prior;

            // Calculate likelihood for each selected attribute
            foreach ($selectedAttributes as $attributeId) {
                $trainingData = TrainingData::where('project_id', $project->id)
                    ->where('class_id', $class->id)
                    ->where('attribute_id', $attributeId)
                    ->first();

                // Bernoulli Naive Bayes with Laplace Smoothing
                $likelihood = $trainingData && $trainingData->is_associated ? 0.9 : 0.1;
                $score *= $likelihood;

                $attribute = $project->attributes->find($attributeId);
                $classSteps['likelihoods'][] = [
                    'attribute_name' => $attribute->name,
                    'attribute_code' => $attribute->code,
                    'value' => $likelihood,
                    'is_associated' => $trainingData ? $trainingData->is_associated : false
                ];
            }

            $classSteps['raw_score'] = $score;
            $calculationSteps[] = $classSteps;

            $results[] = [
                'class_id' => $class->id,
                'class_name' => $class->name,
                'class_code' => $class->code,
                'score' => $score
            ];
        }

        // Normalize scores to percentages
        $totalScore = array_sum(array_column($results, 'score'));
        
        if ($totalScore > 0) {
            foreach ($results as &$result) {
                $result['percentage'] = ($result['score'] / $totalScore) * 100;
            }
            
            foreach ($calculationSteps as &$step) {
                $step['percentage'] = ($step['raw_score'] / $totalScore) * 100;
            }
        } else {
            foreach ($results as &$result) {
                $result['percentage'] = 0;
            }
            
            foreach ($calculationSteps as &$step) {
                $step['percentage'] = 0;
            }
        }

        // Sort by percentage descending
        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
        usort($calculationSteps, fn($a, $b) => $b['percentage'] <=> $a['percentage']);

        return response()->json([
            'results' => $results,
            'calculation_steps' => $calculationSteps,
            'selected_count' => count($selectedAttributes)
        ]);
    }
}

