<?php

namespace App\Models;

use App\Models\Scopes\OrderedCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy(OrderedCodeScope::class)]
class SubActivity extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'sector_code',
    'activity_code',
    'code',
    'sequence',
    'name',
    'description',
    'outcome',
    'indicator',
    'type',
    'piece',
    'tags',
    'implementer',
    'spm',
  ];

  public function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'sector_code', 'code');
  }

  public function activity(): BelongsTo
  {
    return $this->belongsTo(Activity::class, 'activity_code', 'code');
  }
}
