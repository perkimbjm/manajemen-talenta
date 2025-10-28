<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
  use HasUuids, Compoships;

  protected $fillable = [
    'code',
    'name',
    'group',
    'order',
    'description',
  ];

  public function positions()
  {
    return $this->hasMany(Position::class, ['occupation_type_code', 'level'], ['occupation_type_code', 'level']);
  }
}
