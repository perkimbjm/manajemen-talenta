<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'sector_code',
    'program_code',
    'zone_id',
    'code',
    'sequence',
    'name',
    'description',
  ];


  public function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'sector_code', 'code');
  }

  public function program(): BelongsTo
  {
    return $this->belongsTo(Program::class, 'program_code', 'code');
  }
}
