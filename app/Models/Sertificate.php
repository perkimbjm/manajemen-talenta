<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertificate extends Model
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
        'description' => 'Sertifikat PBJ/Keahlian Lainnya Level Utama/Lanjut dan/atau Sertifikat PPK Tipe A/B',
        'name' => 'Sertifikat PBJ/Keahlian Lainnya Level Utama/Lanjut',
        'value' => 100,
      ],
      [
        'description' => 'Sertifikat PBJ/Keahlian Lainnya Level Utama/Lanjut dan/atau Sertifikat PPK Tipe A/B',
        'name' => 'Sertifikat PPK Tipe A/B',
        'value' => 100,
      ],
      [
        'description' => 'Sertifikat PBJ/Keahlian Lainnya Level Madya/menengah dan/atau Sertifikat PPK Tipe C',
        'name' => 'Sertifikat PBJ/Keahlian Lainnya Level Madya/menengah',
        'value' => 80,
      ],
      [
        'description' => 'Sertifikat PBJ/Keahlian Lainnya Level Madya/menengah dan/atau Sertifikat PPK Tipe C',
        'name' => 'Sertifikat PPK Tipe C',
        'value' => 80,
      ],
      [
        'description' => 'Sertifikat PBJ/Keahlian Lainnya Level Dasar (level 1)',
        'name' => 'Sertifikat PBJ/Keahlian Lainnya Level Dasar (level 1)',
        'value' => 60,
      ],
    ]);
  }


  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }
}
