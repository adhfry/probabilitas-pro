<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'x_label',
        'y_label',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class);
    }

    public function trainingData(): HasMany
    {
        return $this->hasMany(TrainingData::class);
    }
}
