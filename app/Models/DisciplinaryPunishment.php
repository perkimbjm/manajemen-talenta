<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplinaryPunishment extends Model
{
  use HasUuids;

  protected $fillable = [
    'nip',
    'year',
    'register_date',
    'end_date',
    'description'
  ];

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }
}
