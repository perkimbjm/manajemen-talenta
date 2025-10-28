<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;

class Position extends Model implements HasMedia
{
  use HasUuids, Compoships, InteractsWithMedia;

  protected $fillable = [
    'id',
    'name',
    'type',
    'grade',
    'level',
    'unit_code',
    'root_code',
    'occupation_type_code',
    'occupation_code',
    'parent_id',
    'description',
    'sequence',
    'tags',
    'status',
  ];

  public function unit(): BelongsTo
  {
    return $this->belongsTo(Unit::class, 'unit_code', 'code');
  }

  public function root_unit(): BelongsTo
  {
    return $this->belongsTo(Unit::class, 'root_code', 'code');
  }

  public function stage()
  {
    return $this->belongsTo(Stage::class, ['occupation_type_code', 'level'], ['occupation_type_code', 'level']);
  }
}
