<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SkpReport extends Model
{
  use HasUuids;

  protected $fillable = [
    'type_id',
    'nip',
    'year',
    'start_period',
    'end_period',
    'work_behavior',
    'work_result',
    'final_result',
    'skp_unor_id',
    'skp_unor',
    'skp_unor_induk',
    'skp_jabatan',
    'skp_jenis_jabatan',
    'is_skp_plt_plh_pjb',
    'golru',
    'rated_at',
  ];
}
