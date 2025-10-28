<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssessmentElement extends Model
{
  use HasUuids;

  protected $fillable = [
    'name',
    'group',
    'weight',
    'description',
  ];
}
