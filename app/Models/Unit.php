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
class Unit extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'sector_code',
    'parent_code',
    'root_code',
    'code',
    'type',
    'name',
    'acronym',
    'level',
    'tags',
    'sequence',
    'description',
  ];

  protected function casts()
  {
    return [
      'tags' => 'array',
    ];
  }

  public function sectors(): BelongsToMany
  {
    return $this->belongsToMany(
      related: Sector::class,
      table: 'unit_sectors',
      foreignPivotKey: 'unit_code',
      relatedPivotKey: 'sector_code',
      parentKey: 'code',
      relatedKey: 'code',
    )
      ->withPivot(['order'])
      ->orderByPivot('order');
  }
}
