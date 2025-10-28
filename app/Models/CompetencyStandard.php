<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CompetencyStandard extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'description',
    'file_disk',
    'file_path',
    'file_type',
    'level',
  ];

  public function standardable(): MorphTo
  {
    return $this->morphTo();
  }
}
