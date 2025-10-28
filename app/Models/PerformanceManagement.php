<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerformanceManagement extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'nip',
    'specific_value',
    'year',
  ];

  public float $weight = 1;

  public static function getEvaluations()
  {
    return collect([
      [
        'name' => 'Sangat Baik',
        'value' => 100,
      ],
      [
        'name' => 'Baik',
        'value' => 80,
      ],
      [
        'name' => 'Butuh Perbaikan',
        'value' => 60,
      ],
      [
        'name' => 'Kurang',
        'value' => 40,
      ],
      [
        'name' => 'Sangat Kurang',
        'value' => 20,
      ],
    ]);
  }

  public function evaluation(): Attribute
  {
    return Attribute::make(
      get: fn($_, $attributes) => $this->getEvaluations()->filter(fn($eval) => $eval['value'] <= $attributes['specific_value'])->first(),
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
