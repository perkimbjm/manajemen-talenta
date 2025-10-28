<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recap extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'year',
    'affair_id',
    'zone_id',
    'sector_code',
    'unit_code',
    'program_code',
    'activity_code',
    'sub_activity_code',
    'fund_code',
    'expense_code',
    'budget',
    'sequence',
  ];

  public function affair(): BelongsTo
  {
    return $this->belongsTo(Affair::class);
  }

  public function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'sector_code', 'code');
  }

  public function unit(): BelongsTo
  {
    return $this->belongsTo(Unit::class, 'unit_code', 'code');
  }

  public function program(): BelongsTo
  {
    return $this->belongsTo(Program::class, 'program_code', 'code');
  }

  public function activity(): BelongsTo
  {
    return $this->belongsTo(Activity::class, 'activity_code', 'code');
  }

  public function subActivity(): BelongsTo
  {
    return $this->belongsTo(SubActivity::class, 'sub_activity_code', 'code');
  }

  public function fund(): BelongsTo
  {
    return $this->belongsTo(Fund::class, 'fund_code', 'code');
  }

  public function expense(): BelongsTo
  {
    return $this->belongsTo(Expense::class, 'expense_code', 'code');
  }
}
