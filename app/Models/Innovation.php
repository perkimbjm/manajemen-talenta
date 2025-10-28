<?php

namespace App\Models;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Innovation extends Model implements HasMedia
{
  use InteractsWithMedia;
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'nip',
    'scope',
    'name',
    'description',
    'value',
    'status',
    'year',
  ];

  public float $weight = 0.20;

  public static function getScopes()
  {
    return collect([
      [
            'name' => 'Internasional',
            'value' => 100,
      ],
      [
        'name' => 'Nasional',
        'value' => 75,
      ],
      [
        'name' => 'Lintas Instansi',
        'value' => 50,
      ],
      [
        'name' => 'Lingkup Instansi',
        'value' => 25,
      ],
      [
        'name' => 'Tidak Pernah',
        'value' => 0,
      ],
    ]);
  }

  public function computedValue(): Attribute
  {
    return Attribute::make(
      get: fn($_, $attributes) => $this->weight * $attributes['value']
    );
  }

  public function statusLabel(): Attribute
  {
    return Attribute::make(
      get: fn($_, $attributes) => match ($attributes['status']) {
        0 => 'Belum Diverifikasi',
        1 => 'Ditolak',
        2 => 'Diverifikasi',
      }
    );
  }

  protected function year(): Attribute
  {
    return Attribute::make(
      get: fn($year) => $year,
      set: fn($year) => $year ?? date('Y')
    );
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }
}
