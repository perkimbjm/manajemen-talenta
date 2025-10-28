<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
  protected $fillable = [
    'id',
    'name',
    'standard_value',
    'description',
  ];
}
