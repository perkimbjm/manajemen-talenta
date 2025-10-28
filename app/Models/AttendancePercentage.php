<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AttendancePercentage extends Model
{
  use HasUuids;

  protected $fillable = [
    'nip',
    'year',
    'januari',
    'februari',
    'maret',
    'april',
    'mei',
    'juni',
    'juli',
    'agustus',
    'september',
    'oktober',
    'november',
    'desember',
  ];

  public static function getMonthList()
  {
    return collect([
      [
        'id' => 1,
        'name' => 'Januari'
      ],
      [
        'id' => 2,
        'name' => 'Februari'
      ],
      [
        'id' => 3,
        'name' => 'Maret'
      ],
      [
        'id' => 4,
        'name' => 'April'
      ],
      [
        'id' => 5,
        'name' => 'Mei'
      ],
      [
        'id' => 6,
        'name' => 'Juni'
      ],
      [
        'id' => 7,
        'name' => 'Juli'
      ],
      [
        'id' => 8,
        'name' => 'Agustus'
      ],
      [
        'id' => 9,
        'name' => 'September'
      ],
      [
        'id' => 10,
        'name' => 'Oktober'
      ],
      [
        'id' => 11,
        'name' => 'November'
      ],
      [
        'id' => 12,
        'name' => 'Desember'
      ],
    ]);
  }

  public function summary(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $range_end = 9; // September

        if (!$range_end) {
          $range_end = date('m') - 1;
        }

        $months = self::getMonthList()->filter(fn($m) => $m['id'] <= $range_end);
        $sum_total = 0;

        foreach ($months as $month) {
          $sum_total += $attributes[strtolower($month['name'])];
        }

        return round($sum_total / $months->count(), 2);
      }
    );
  }
}
