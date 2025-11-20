<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'project_id',
        'code',
        'name',
        'prior_probability',
    ];

    protected $casts = [
        'prior_probability' => 'float',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function trainingData(): HasMany
    {
        return $this->hasMany(TrainingData::class, 'class_id');
    }
}
