<?php

namespace App\Models;

use App\Models\Scopes\OrderedCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy(OrderedCodeScope::class)]
class Program extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'sector_code',
    'zone_id',
    'code',
    'sequence',
    'name',
    'description',
  ];

  /**
   * Get zones collection or single object based on id.
   *
   * If the parameter is null, it will return a collection of zones.
   * If the parameter is not null, it will return a single zone object.
   *
   * @param int|null $id
   * @return \Illuminate\Support\Collection|\stdClass
   */
  public static function zones(?int $id = null)
  {
    $zones = collect([
      (object) [
        'id' => 1,
        'label' => 'PUSAT',
        'level' => 1,
      ],
      (object) [
        'id' => 2,
        'label' => 'PROVINSI',
        'level' => 2,
      ],
      (object) [
        'id' => 3,
        'label' => 'KABUPATEN / KOTA',
        'level' => 3,
      ],
    ]);

    if (is_null($id)) return $zones;

    return $zones->firstOrFail('id', $id);
  }

  public function zone(): Attribute
  {
    return Attribute::make(
      get: fn($_, $attributes) => self::zones($attributes['zone_id']),
    );
  }

  public function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'sector_code', 'code');
  }
}
