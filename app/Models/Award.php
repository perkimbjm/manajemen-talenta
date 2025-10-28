<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Award extends Model
{
  use HasUuids;

  protected $fillable = [
    'nip',
    'type',
    'name',
    'status',
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
        'description' => 'Peraih penghargaan di lingkup Internasional ',
        'name' => 'Lingkup Internasional',
        'value' => 100,
      ],
      [
        'description' => 'Peraih penghargaan di lingkup Nasional',
        'name' => 'Lingkup Nasional',
        'value' => 75,
      ],
      [
        'description' => 'Peraih penghargaan di lingkup lintas Instansi',
        'name' => 'Lingkup Lintas Instansi',
        'value' => 50,
      ],
      [
        'description' => 'Peraih penghargaan di lingkup Instansi',
        'name' => 'Lingkup Instansi',
        'value' => 25,
      ],
      [
        'description' => 'Tidak Pernah Mendapatkan Penghargaan',
        'name' => 'Tidak Pernah',
        'value' => 0,
      ],
    ]);
  }


  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }
}
