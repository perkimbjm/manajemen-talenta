<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'nip',
    'superior_raters',
    'peer_raters',
    'subordinate_raters',
    'superior_value',
    'peer_value',
    'subordinate_value',
    'specific_value',
    'status',
    'notes',
    'year',
  ];

  protected $casts = [
    'superior_raters' => 'integer',
    'peer_raters' => 'integer',
    'subordinate_raters' => 'integer',
    'superior_value' => 'float',
    'peer_value' => 'float',
    'subordinate_value' => 'float',
    'specific_value' => 'float',
    'status' => 'integer',
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
      get: fn($_, $attributes) => $this
        ->getEvaluations()
        ->filter(fn($eval) => $eval['value'] <= $this->resolveAggregateScore($attributes))
        ->first(),
    );
  }

  public function aggregateScore(): Attribute
  {
    return Attribute::make(
      get: fn($_, $attributes) => $this->resolveAggregateScore($attributes),
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

  /**
   * Hitung nilai akhir 360 berdasarkan jumlah penilai tiap kelompok.
   */
  private function resolveAggregateScore(array $attributes): float
  {
    $groups = collect([
      [
        'value' => (float) ($attributes['superior_value'] ?? 0),
        'raters' => (int) ($attributes['superior_raters'] ?? 0),
      ],
      [
        'value' => (float) ($attributes['peer_value'] ?? 0),
        'raters' => (int) ($attributes['peer_raters'] ?? 0),
      ],
      [
        'value' => (float) ($attributes['subordinate_value'] ?? 0),
        'raters' => (int) ($attributes['subordinate_raters'] ?? 0),
      ],

    ])->filter(fn($group) => $group['value'] > 0 || $group['raters'] > 0)
      ->map(function ($group) {
        $weight = $group['raters'] > 0 ? $group['raters'] : 1;
        return [
          'value' => $group['value'],
          'weight' => $weight,
        ];
      });

    if ($groups->isEmpty()) {
      return round((float) ($attributes['specific_value'] ?? 0), 2);
    }

    $total_weight = $groups->sum('weight');
    if ($total_weight === 0) {
      return round((float) ($attributes['specific_value'] ?? 0), 2);
    }

    $weighted_value = $groups->reduce(fn($carry, $group) => $carry + ($group['value'] * $group['weight']), 0);

    return round($weighted_value / $total_weight, 2);
  }
}
