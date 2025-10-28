<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentPoolBox extends Model
{
  protected $fillable = [
    'id',
    'label',
    'order',
    'hsl',
    'color',
    'description',
    'min_potential_value',
    'max_potential_value',
    'min_performance_value',
    'max_performance_value',
  ];

  public function assessments(): HasMany
  {
    return $this->hasMany(Assessment::class, 'box_id', 'id');
  }
}
