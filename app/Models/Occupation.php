<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Occupation extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;
  use HasUuids;

  protected $fillable = [
    'code',
    'name',
    'type_code',
    'echelon_code',
    'nomenclature',
    'group',
    'sequence',
    'grade',
    'level',
    'description',
    'tags',
  ];

  protected $casts = [
    'tags' => 'array'
  ];

  public function type(): BelongsTo
  {
    return $this->belongsTo(OccupationType::class, 'type_code', 'code');
  }

  public function competencyStandards(): MorphMany
  {
    return $this->morphMany(CompetencyStandard::class, 'standardable');
  }
}
