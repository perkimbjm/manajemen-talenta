<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
  use HasUuids;

  protected $fillable = [
    'nip',
    'register_number',
    'code',
    'label',
    'value',
    'skj',
    'gap',
    'description',
    'recommendation',
    'manajerial',
    'kultural',
    'ket_manajerial',
    'ket_kultural',
  ];
}
