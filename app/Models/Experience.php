<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
  use HasUuids;

  protected $fillable = [
    'nip',
    'type',
    'name',
    'status',
    'value',
    'description',
  ];



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

  public static function getTypes()
  {
    return collect([
      [
        'description' => 'Menjadi Ketua organisasi dalam lingkup nasional',
        'name' => 'Menjadi Ketua organisasi dalam lingkup nasional',
        'value' => 100,
      ],
      [
        'description' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup nasional',
        'name' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup nasional',
        'value' => 80,
      ],
      [
        'description' => 'Menjadi Ketua organisasi dalam lingkup wilayah Propinsi',
        'name' => 'Menjadi Ketua organisasi dalam lingkup wilayah Propinsi',
        'value' => 70,
      ],
      [
        'description' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup wilayah Propinsi',
        'name' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup wilayah Propinsi',
        'value' => 60,
      ],
      [
        'description' => 'Menjadi Ketua organisasi dalam lingkup wilayah Kota/Kabupaten',
        'name' => 'Menjadi Ketua organisasi dalam lingkup wilayah Kota/Kabupaten',
        'value' => 50,
      ],
      [
        'description' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup wilayah Kota/Kabupaten',
        'name' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup wilayah Kota/Kabupaten',
        'value' => 40,
      ],
    ]);
  }


  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }
}
