<?php

namespace App\Models;

use App\Models\Scopes\OrderedCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy(OrderedCodeScope::class)]
class Sector extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'affair_id',
    'code',
    'name',
    'description',
  ];

  public function affair(): BelongsTo
  {
    return $this->belongsTo(Affair::class);
  }

  public function units(): BelongsToMany
  {
    return $this->belongsToMany(
      related: Unit::class,
      table: 'unit_sectors',
      relatedPivotKey: 'unit_code',
      foreignPivotKey: 'sector_code',
      parentKey: 'code',
      relatedKey: 'code',
    );
  }
}
