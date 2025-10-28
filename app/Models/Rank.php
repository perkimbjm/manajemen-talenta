<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
  protected $fillable = [
    'id',
    'name',
    'group',
    'section',
    'description',
  ];
}
