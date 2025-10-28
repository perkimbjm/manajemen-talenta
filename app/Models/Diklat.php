<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diklat extends Model
{
  use HasUuids;

  protected $fillable = [
    'nip',
    'code',
    'type',
    'rank',
    'year',
    'date',
    'name',
    'letter_number',
    'description',
    'status',
  ];

  public static function getStatuses()
  {
    return [
      0 => 'Sedang Mengikuti',
      1 => 'Sudah Mengikuti',
    ];
  }

  public static function getTypes()
  {
    return [
      'Pim',
      'Fungsional',
    ];
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }

  public function statusLabel(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $statuses = self::getStatuses();

        return @$statuses[$attributes['status']];
      }
    );
  }
}
