<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model implements HasMedia
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
    'as',
    'status',
    'year',
  ];

  public float $weight = 0.20;

  public static function getScopes()
  {
    return collect([
      [
        'name' => 'Provinsi',
        'value' => 80,
      ],
      [
        'name' => 'Kabupaten/Kota',
        'value' => 60,
      ],
      [
        'name' => 'OPD',
        'value' => 40,
      ],
    ]);
  }

  protected function year(): Attribute
  {
    return Attribute::make(
      get: fn($year) => $year,
      set: fn($year) => $year ?? date('Y')
    );
  }

  public static function getPositions()
  {
    return collect([
      [
        'name' => 'Ketua',
        'value' => 20,
      ],
      [
        'name' => 'Anggota',
        'value' => 10,
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


  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }
}
