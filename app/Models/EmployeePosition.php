<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePosition extends Model
{
  use HasUuids;

  protected $fillable = [
    'type',
    'nip',
    'name',
    'echelon',
    'decree_number',
    'decree_date',
    'inauguration_date',
    'tmt_date',
    'description',
  ];

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }
}
