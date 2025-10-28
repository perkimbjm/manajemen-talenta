<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
  protected $fillable = [
    'group',
    'prev_id',
    'prev_name',
    'current_id',
    'current_name',
  ];
}
