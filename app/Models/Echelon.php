<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Echelon extends Model
{
  protected $fillable = [
    'id',
    'name',
    'group',
    'section',
    'order',
    'description',
  ];
}
