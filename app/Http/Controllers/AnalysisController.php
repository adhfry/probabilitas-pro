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
use Mpdf\Mpdf;

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
        $attributes = $project->attributes;
        $results = [];
        $calculationSteps = [];
        
        // Step 1: Build frequency table for all classes and attributes
        $frequencyTable = [];
        $classFrequencies = [];
        
        foreach ($classes as $class) {
            // Count total associations for this class
            $totalAssociations = TrainingData::where('project_id', $project->id)
                ->where('class_id', $class->id)
                ->where('is_associated', true)
                ->count();
            
            $classFrequencies[$class->id] = [
                'class_name' => $class->name,
                'class_code' => $class->code,
                'total' => $totalAssociations,
                'prior' => $class->prior_probability > 0 ? $class->prior_probability : (1 / $classes->count())
            ];
            
            $frequencyTable[$class->id] = [];
            
            foreach ($attributes as $attr) {
                $trainingData = TrainingData::where('project_id', $project->id)
                    ->where('class_id', $class->id)
                    ->where('attribute_id', $attr->id)
                    ->first();
                
                $isAssociated = $trainingData && $trainingData->is_associated;
                
                $frequencyTable[$class->id][$attr->id] = [
                    'attribute_name' => $attr->name,
                    'attribute_code' => $attr->code,
                    'is_associated' => $isAssociated,
                    'count' => $isAssociated ? 1 : 0
                ];
            }
        }

        // Step 2: Calculate for each class
        foreach ($classes as $class) {
            $prior = $class->prior_probability > 0 ? $class->prior_probability : (1 / $classes->count());
            $score = $prior;
            
            $classSteps = [
                'class_name' => $class->name,
                'class_code' => $class->code,
                'class_id' => $class->id,
                'prior' => $prior,
                'prior_fraction' => [
                    'numerator' => 1,
                    'denominator' => $classes->count()
                ],
                'likelihoods' => [],
                'frequency_data' => $classFrequencies[$class->id]
            ];

            // Calculate likelihood for each selected attribute
            $likelihoodProduct = 1.0;
            
            foreach ($selectedAttributes as $attributeId) {
                $trainingData = TrainingData::where('project_id', $project->id)
                    ->where('class_id', $class->id)
                    ->where('attribute_id', $attributeId)
                    ->first();

                $isAssociated = $trainingData && $trainingData->is_associated;
                
                // Using probability with Laplace smoothing
                // P(Xi|C) = (count(Xi in C) + 1) / (count(C) + 2)
                $countAttribute = $isAssociated ? 1 : 0;
                $countClass = $classFrequencies[$class->id]['total'];
                
                // Simplified for binary case (yes/no)
                $likelihood = $isAssociated ? 0.9 : 0.1;
                
                $likelihoodProduct *= $likelihood;
                $score *= $likelihood;

                $attribute = $attributes->find($attributeId);
                
                $classSteps['likelihoods'][] = [
                    'attribute_name' => $attribute->name,
                    'attribute_code' => $attribute->code,
                    'attribute_id' => $attributeId,
                    'value' => $likelihood,
                    'is_associated' => $isAssociated,
                    'count_attribute' => $countAttribute,
                    'count_class' => $countClass,
                    'formula' => $isAssociated ? 
                        "P({$attribute->code}|{$class->code}) = 0.9" : 
                        "P({$attribute->code}|{$class->code}) = 0.1"
                ];
            }

            $classSteps['likelihood_product'] = $likelihoodProduct;
            $classSteps['raw_score'] = $score;
            $calculationSteps[] = $classSteps;

            $results[] = [
                'class_id' => $class->id,
                'class_name' => $class->name,
                'class_code' => $class->code,
                'score' => $score
            ];
        }

        // Step 3: Normalize scores to percentages
        $totalScore = array_sum(array_column($results, 'score'));
        
        if ($totalScore > 0) {
            foreach ($results as &$result) {
                $result['percentage'] = ($result['score'] / $totalScore) * 100;
            }
            
            foreach ($calculationSteps as &$step) {
                $step['percentage'] = ($step['raw_score'] / $totalScore) * 100;
                $step['total_score'] = $totalScore;
            }
        } else {
            foreach ($results as &$result) {
                $result['percentage'] = 0;
            }
            
            foreach ($calculationSteps as &$step) {
                $step['percentage'] = 0;
                $step['total_score'] = 0;
            }
        }

        // Sort by percentage descending
        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
        usort($calculationSteps, fn($a, $b) => $b['percentage'] <=> $a['percentage']);

        return response()->json([
            'results' => $results,
            'calculation_steps' => $calculationSteps,
            'selected_count' => count($selectedAttributes),
            'frequency_table' => $frequencyTable,
            'class_frequencies' => $classFrequencies,
            'total_score' => $totalScore
        ]);
    }

    public function exportPdf(Request $request, Project $project)
    {
        $validated = $request->validate([
            'selected_attributes' => 'required|array',
            'selected_attributes.*' => 'exists:attributes,id',
        ]);

        $selectedAttributes = $validated['selected_attributes'];
        
        // Run the same analysis logic
        $classes = $project->classes;
        $attributes = $project->attributes;
        $results = [];
        $calculationSteps = [];
        
        // Step 1: Build frequency table
        $frequencyTable = [];
        $classFrequencies = [];
        
        foreach ($classes as $class) {
            $totalAssociations = TrainingData::where('project_id', $project->id)
                ->where('class_id', $class->id)
                ->where('is_associated', true)
                ->count();
            
            $classFrequencies[$class->id] = [
                'class_name' => $class->name,
                'class_code' => $class->code,
                'total' => $totalAssociations,
                'prior' => $class->prior_probability > 0 ? $class->prior_probability : (1 / $classes->count())
            ];
            
            $frequencyTable[$class->id] = [];
            
            foreach ($attributes as $attr) {
                $trainingData = TrainingData::where('project_id', $project->id)
                    ->where('class_id', $class->id)
                    ->where('attribute_id', $attr->id)
                    ->first();
                
                $isAssociated = $trainingData && $trainingData->is_associated;
                
                $frequencyTable[$class->id][$attr->id] = [
                    'attribute_name' => $attr->name,
                    'attribute_code' => $attr->code,
                    'is_associated' => $isAssociated,
                    'count' => $isAssociated ? 1 : 0
                ];
            }
        }

        // Step 2: Calculate for each class
        foreach ($classes as $class) {
            $prior = $class->prior_probability > 0 ? $class->prior_probability : (1 / $classes->count());
            $score = $prior;
            
            $classSteps = [
                'class_name' => $class->name,
                'class_code' => $class->code,
                'class_id' => $class->id,
                'prior' => $prior,
                'prior_fraction' => [
                    'numerator' => 1,
                    'denominator' => $classes->count()
                ],
                'likelihoods' => [],
                'frequency_data' => $classFrequencies[$class->id]
            ];

            $likelihoodProduct = 1.0;
            
            foreach ($selectedAttributes as $attributeId) {
                $trainingData = TrainingData::where('project_id', $project->id)
                    ->where('class_id', $class->id)
                    ->where('attribute_id', $attributeId)
                    ->first();

                $isAssociated = $trainingData && $trainingData->is_associated;
                $countAttribute = $isAssociated ? 1 : 0;
                $countClass = $classFrequencies[$class->id]['total'];
                $likelihood = $isAssociated ? 0.9 : 0.1;
                
                $likelihoodProduct *= $likelihood;
                $score *= $likelihood;

                $attribute = $attributes->find($attributeId);
                
                $classSteps['likelihoods'][] = [
                    'attribute_name' => $attribute->name,
                    'attribute_code' => $attribute->code,
                    'attribute_id' => $attributeId,
                    'value' => $likelihood,
                    'is_associated' => $isAssociated,
                    'count_attribute' => $countAttribute,
                    'count_class' => $countClass,
                ];
            }

            $classSteps['likelihood_product'] = $likelihoodProduct;
            $classSteps['raw_score'] = $score;
            $calculationSteps[] = $classSteps;

            $results[] = [
                'class_id' => $class->id,
                'class_name' => $class->name,
                'class_code' => $class->code,
                'score' => $score
            ];
        }

        // Step 3: Normalize scores
        $totalScore = array_sum(array_column($results, 'score'));
        
        if ($totalScore > 0) {
            foreach ($results as &$result) {
                $result['percentage'] = ($result['score'] / $totalScore) * 100;
            }
            
            foreach ($calculationSteps as &$step) {
                $step['percentage'] = ($step['raw_score'] / $totalScore) * 100;
                $step['total_score'] = $totalScore;
            }
        }

        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
        usort($calculationSteps, fn($a, $b) => $b['percentage'] <=> $a['percentage']);

        // Get selected attribute names
        $selectedAttrNames = [];
        foreach ($selectedAttributes as $attrId) {
            $attr = $attributes->find($attrId);
            $selectedAttrNames[] = ['code' => $attr->code, 'name' => $attr->name];
        }

        // Generate PDF
        $html = $this->generatePdfHtml($project, $selectedAttrNames, $results, $calculationSteps, $frequencyTable, $classFrequencies);
        
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);

        $mpdf->SetTitle('Laporan Analisis - ' . $project->title);
        $mpdf->SetAuthor('Probabilitas Pro');
        $mpdf->WriteHTML($html);
        
        return response($mpdf->Output('', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Analisis_' . str_replace(' ', '_', $project->title) . '_' . date('Ymd_His') . '.pdf"');
    }

    private function generatePdfHtml($project, $selectedAttributes, $results, $calculationSteps, $frequencyTable, $classFrequencies)
    {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10pt;
            color: #333;
        }
        h1 {
            color: #0369a1;
            font-size: 18pt;
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 3px solid #0284c7;
            padding-bottom: 10px;
        }
        h2 {
            color: #0284c7;
            font-size: 14pt;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e0f2fe;
            padding-bottom: 5px;
        }
        h3 {
            color: #0c4a6e;
            font-size: 12pt;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            color: #64748b;
            font-size: 9pt;
        }
        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 10px;
            margin: 10px 0;
        }
        .info-label {
            font-weight: bold;
            color: #0369a1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }
        th {
            background: #0284c7;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .formula {
            background: #f1f5f9;
            border-left: 4px solid #0284c7;
            padding: 8px 12px;
            margin: 8px 0;
            font-family: "Courier New", monospace;
            font-size: 9pt;
        }
        .result-box {
            background: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
        }
        .result-title {
            font-size: 12pt;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 5px;
        }
        .result-value {
            font-size: 16pt;
            font-weight: bold;
            color: #059669;
        }
        .step-number {
            display: inline-block;
            background: #0284c7;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 25px;
            font-weight: bold;
            margin-right: 8px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-yes {
            background: #dcfce7;
            color: #166534;
        }
        .badge-no {
            background: #fee2e2;
            color: #991b1b;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            text-align: center;
            color: #94a3b8;
            font-size: 8pt;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <h1>LAPORAN ANALISIS PROBABILITAS</h1>
    <div class="header-info">
        Sistem Pakar Naive Bayes - Probabilitas Pro<br>
        Tanggal: ' . date('d F Y, H:i') . ' WIB
    </div>

    <div class="info-box">
        <div><span class="info-label">Nama Proyek:</span> ' . htmlspecialchars($project->title) . '</div>
        <div><span class="info-label">Deskripsi:</span> ' . htmlspecialchars($project->description ?: '-') . '</div>
        <div><span class="info-label">Label Prediktor:</span> ' . htmlspecialchars($project->x_label) . '</div>
        <div><span class="info-label">Label Kelas:</span> ' . htmlspecialchars($project->y_label) . '</div>
        <div><span class="info-label">Jumlah ' . htmlspecialchars($project->x_label) . ' Dipilih:</span> ' . count($selectedAttributes) . '</div>
    </div>

    <h2>1. DATA INPUT</h2>
    <h3>' . htmlspecialchars($project->x_label) . ' yang Dipilih:</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="20%">Kode</th>
                <th width="70%">Nama ' . htmlspecialchars($project->x_label) . '</th>
            </tr>
        </thead>
        <tbody>';
        
        $no = 1;
        foreach ($selectedAttributes as $attr) {
            $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td><strong>' . htmlspecialchars($attr['code']) . '</strong></td>
                <td>' . htmlspecialchars($attr['name']) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>

    <h2>2. TABEL FREKUENSI</h2>
    <p>Tabel berikut menunjukkan hubungan antara ' . htmlspecialchars($project->x_label) . ' dengan setiap ' . htmlspecialchars($project->y_label) . '.</p>
    
    <table>
        <thead>
            <tr>
                <th>' . htmlspecialchars($project->x_label) . '</th>';
                
        foreach ($project->classes as $class) {
            $html .= '<th>' . htmlspecialchars($class->code) . '</th>';
        }
        
        $html .= '
            </tr>
        </thead>
        <tbody>';
        
        foreach ($project->attributes as $attr) {
            $html .= '<tr><td><strong>' . htmlspecialchars($attr->code) . '</strong> - ' . htmlspecialchars($attr->name) . '</td>';
            foreach ($project->classes as $class) {
                $isAssociated = $frequencyTable[$class->id][$attr->id]['is_associated'];
                $html .= '<td><span class="badge ' . ($isAssociated ? 'badge-yes' : 'badge-no') . '">' . 
                         ($isAssociated ? 'Ya' : 'Tidak') . '</span></td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2>3. PERHITUNGAN PROBABILITAS</h2>
    <p>Menggunakan <strong>Teorema Naive Bayes</strong> untuk menghitung probabilitas setiap kelas:</p>
    
    <div class="formula">
        P(C<sub>k</sub>|X) ∝ P(C<sub>k</sub>) × ∏<sub>i=1</sub><sup>n</sup> P(X<sub>i</sub>|C<sub>k</sub>)
    </div>
    
    <p><strong>Keterangan:</strong></p>
    <ul>
        <li>P(C<sub>k</sub>|X) = Probabilitas kelas C<sub>k</sub> jika ' . htmlspecialchars($project->x_label) . ' X terjadi</li>
        <li>P(C<sub>k</sub>) = Probabilitas prior kelas C<sub>k</sub></li>
        <li>P(X<sub>i</sub>|C<sub>k</sub>) = Probabilitas ' . htmlspecialchars($project->x_label) . ' X<sub>i</sub> jika kelas C<sub>k</sub></li>
        <li>∏ = Perkalian (product) dari semua likelihood</li>
    </ul>';

        $stepNum = 1;
        foreach ($calculationSteps as $step) {
            $html .= '
    <h3><span class="step-number">' . $stepNum++ . '</span>' . htmlspecialchars($step['class_code']) . ' - ' . htmlspecialchars($step['class_name']) . '</h3>
    
    <p><strong>a) Probabilitas Prior P(' . htmlspecialchars($step['class_code']) . '):</strong></p>
    <div class="formula">
        P(' . htmlspecialchars($step['class_code']) . ') = 1 / ' . $step['prior_fraction']['denominator'] . ' = ' . number_format($step['prior'], 4) . '
    </div>
    
    <p><strong>b) Probabilitas Likelihood:</strong></p>
    <table>
        <thead>
            <tr>
                <th width="30%">Atribut</th>
                <th width="25%">Kode</th>
                <th width="20%">Terkait?</th>
                <th width="25%">P(X<sub>i</sub>|' . htmlspecialchars($step['class_code']) . ')</th>
            </tr>
        </thead>
        <tbody>';
            
            foreach ($step['likelihoods'] as $likelihood) {
                $html .= '
            <tr>
                <td>' . htmlspecialchars($likelihood['attribute_name']) . '</td>
                <td><strong>' . htmlspecialchars($likelihood['attribute_code']) . '</strong></td>
                <td><span class="badge ' . ($likelihood['is_associated'] ? 'badge-yes' : 'badge-no') . '">' . 
                     ($likelihood['is_associated'] ? 'Ya' : 'Tidak') . '</span></td>
                <td><strong>' . number_format($likelihood['value'], 2) . '</strong></td>
            </tr>';
            }
            
            $html .= '
        </tbody>
    </table>
    
    <p><strong>c) Perhitungan Score:</strong></p>
    <div class="formula">
        Score(' . htmlspecialchars($step['class_code']) . ') = P(' . htmlspecialchars($step['class_code']) . ') × ';
            
            $likelihoodTerms = [];
            foreach ($step['likelihoods'] as $likelihood) {
                $likelihoodTerms[] = 'P(' . htmlspecialchars($likelihood['attribute_code']) . '|' . htmlspecialchars($step['class_code']) . ')';
            }
            $html .= implode(' × ', $likelihoodTerms);
            
            $html .= '<br>
        Score(' . htmlspecialchars($step['class_code']) . ') = ' . number_format($step['prior'], 4) . ' × ';
            
            $likelihoodValues = [];
            foreach ($step['likelihoods'] as $likelihood) {
                $likelihoodValues[] = number_format($likelihood['value'], 2);
            }
            $html .= implode(' × ', $likelihoodValues);
            
            $html .= '<br>
        Score(' . htmlspecialchars($step['class_code']) . ') = ' . sprintf('%.4e', $step['raw_score']) . '
    </div>';
        }

        $html .= '
    <div class="page-break"></div>

    <h2>4. NORMALISASI PROBABILITAS</h2>
    <p>Mengkonversi raw score menjadi persentase:</p>
    
    <div class="formula">
        P(' . htmlspecialchars($project->y_label) . '<sub>i</sub>|Evidence) = Score(' . htmlspecialchars($project->y_label) . '<sub>i</sub>) / Σ Score(C<sub>i</sub>) × 100%
    </div>
    
    <p><strong>Total Score (Σ):</strong> ' . sprintf('%.4e', $calculationSteps[0]['total_score']) . '</p>
    
    <table>
        <thead>
            <tr>
                <th width="15%">Kode</th>
                <th width="40%">' . htmlspecialchars($project->y_label) . '</th>
                <th width="25%">Raw Score</th>
                <th width="20%">Probabilitas</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($calculationSteps as $step) {
            $html .= '
            <tr>
                <td><strong>' . htmlspecialchars($step['class_code']) . '</strong></td>
                <td>' . htmlspecialchars($step['class_name']) . '</td>
                <td>' . sprintf('%.4e', $step['raw_score']) . '</td>
                <td><strong>' . number_format($step['percentage'], 2) . '%</strong></td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>

    <h2>5. HASIL ANALISIS</h2>
    <p>Ranking ' . htmlspecialchars($project->y_label) . ' berdasarkan probabilitas tertinggi:</p>
    
    <table>
        <thead>
            <tr>
                <th width="10%">Rank</th>
                <th width="15%">Kode</th>
                <th width="50%">' . htmlspecialchars($project->y_label) . '</th>
                <th width="25%">Probabilitas</th>
            </tr>
        </thead>
        <tbody>';
        
        $rank = 1;
        foreach ($results as $result) {
            $html .= '
            <tr>
                <td><strong>#' . $rank++ . '</strong></td>
                <td><strong>' . htmlspecialchars($result['class_code']) . '</strong></td>
                <td>' . htmlspecialchars($result['class_name']) . '</td>
                <td><strong>' . number_format($result['percentage'], 2) . '%</strong></td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>

    <div class="result-box">
        <div class="result-title">KESIMPULAN</div>
        <p>Berdasarkan <strong>' . count($selectedAttributes) . ' ' . htmlspecialchars($project->x_label) . '</strong> yang dipilih, sistem mendiagnosa kemungkinan tertinggi adalah:</p>
        <div class="result-value">' . htmlspecialchars($results[0]['class_name']) . '</div>
        <p>dengan tingkat probabilitas <strong>' . number_format($results[0]['percentage'], 2) . '%</strong></p>
    </div>

    <h2>6. INTERPRETASI HASIL</h2>
    <p>Berdasarkan analisis menggunakan metode Naive Bayes, ' . htmlspecialchars($project->y_label) . ' <strong>' . htmlspecialchars($results[0]['class_name']) . '</strong> 
    memiliki probabilitas tertinggi sebesar <strong>' . number_format($results[0]['percentage'], 2) . '%</strong>. Hal ini menunjukkan bahwa dari ' . 
    count($selectedAttributes) . ' ' . htmlspecialchars($project->x_label) . ' yang dipilih, pola yang terbentuk paling sesuai dengan karakteristik 
    ' . htmlspecialchars($results[0]['class_name']) . ' berdasarkan data training yang telah dilatih.</p>
    
    <p><strong>Catatan:</strong> Hasil analisis ini bersifat probabilistik dan sebaiknya digunakan sebagai alat bantu keputusan. 
    Untuk diagnosis yang lebih akurat, disarankan untuk melakukan verifikasi lebih lanjut atau konsultasi dengan ahli di bidang terkait.</p>

    <div class="footer">
        Laporan dibuat secara otomatis oleh <strong>Probabilitas Pro</strong><br>
        © 2025 Ahda Firly Barori - Expert System with Naive Bayes
    </div>
</body>
</html>';

        return $html;
    }
}

