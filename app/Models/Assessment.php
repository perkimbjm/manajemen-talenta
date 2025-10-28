<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assessment extends Model
{
  use HasFactory;
  use HasUuids;

  protected $fillable = [
    'box_id',
    'name',
    'nip',
    'register_number',
    'description',
    'performance_value',
    'potential_value',
    'specific',
    'innovation',
    'year',
    'organizational',
    'extra',
    'potential',
    'competency',
    'track_record',
    'other',
    'manajerial',
    'sosialkultural',
    'teknis',
    'status',
    'performance_preference',
    'potential_preference',
    'upper_competency',
    'recommendation',
    'compatibility',
    'jpm',
  ];

  protected $appends = [
    'get_performance_value',
    'get_potential_value',
  ];

  public function encryptedRegisterNumber(): Attribute
  {
    return Attribute::make(
      get: fn($_, $attributes) => md5($attributes['register_number']),
    );
  }

  public static function getExperienceEvaluations()
  {
    $options = [
      [
        'description' => 'Pengalaman Jabatan lebih dari 7 Jabatan/unit kerja yang berbeda',
        'min_count' => 7,
        'value' => 100,
      ],
      [
        'description' => 'Pengalaman Jabatan antara dari 5-6  Jabatan/unit kerja yang berbeda',
        'min_count' => 5,
        'max_count' => 6,
        'value' => 80,
      ],
      [
        'description' => 'Pengalaman Jabatan antara 3-4  Jabatan/unit kerja yang berbeda',
        'min_count' => 3,
        'max_count' => 4,
        'value' => 60,
      ],
      [
        'description' => 'Pengalaman Jabatan antara 2  Jabatan/unit kerja yang berbeda',
        'min_count' => 2,
        'value' => 40,
      ],
      [
        'description' => 'Pengalaman Jabatan dalam 1  Jabatan/unit kerja yang berbeda',
        'min_count' => 1,
        'value' => 20,
      ],
    ];

    return collect($options);
  }

  public static function getBoxs()
  {
    // X => Potensial
    // Y => Kinerja
    return collect([
      [
        'id' => 1,
        'color' => 'hsl(0,100%,50%)',
        'label' => 1,
        'description' => 'Kinerja di bawah ekspektasi dan potensial rendah',
        'xMin' => 20,
        'xMax' => 46,
        'yMin' => 20,
        'yMax' => 46,
      ],
      [
        'color' => 'hsl(11, 90%, 80%)',
        'label' => 2,
        'id' => 2,
        'description' => 'Kinerja sesuai ekspektasi dan potensial rendah',
        'xMin' => 20,
        'xMax' => 46,
        'yMin' => 46,
        'yMax' => 72,
      ],
      [
        'color' => 'hsl(29, 88%, 77%)',
        'label' => 3,
        'id' => 3,
        'description' => 'Kinerja di bawah ekspektasi dan potensial menengah',
        'xMin' => 46,
        'xMax' => 72,
        'yMin' => 20,
        'yMax' => 46,
      ],
      [
        'color' => 'hsl(60, 100%, 50%)',
        'label' => 4,
        'id' => 4,
        'description' => 'Kinerja di atas ekspektasi dan potensial rendah',
        'xMin' => 20,
        'xMax' => 46,
        'yMin' => 72,
        'yMax' => 100,
      ],
      [
        'color' => 'hsl(45, 100%, 50%)',
        'label' => 5,
        'id' => 5,
        'description' => 'Kinerja sesuai ekspektasi dan potensial menengah',
        'xMin' => 46,
        'xMax' => 72,
        'yMin' => 46,
        'yMax' => 72,
      ],
      [
        'color' => 'hsl(184, 53%, 71%)',
        'label' => 6,
        'id' => 6,
        'description' => 'Kinerja di bawah ekspektasi dan potensial tinggi',
        'xMin' => 72,
        'xMax' => 100,
        'yMin' => 20,
        'yMax' => 46,
      ],
      [
        'color' => 'hsl(137, 53%, 57%)',
        'label' => 7,
        'id' => 7,
        'description' => 'Kinerja di atas ekspektasi dan potensial menengah',
        'xMin' => 46,
        'xMax' => 72,
        'yMin' => 72,
        'yMax' => 100,
      ],
      [
        'color' => 'hsl(136, 53%, 43%)',
        'label' => 8,
        'id' => 8,
        'description' => 'Kinerja sesuai ekspektasi dan potensial tinggi',
        'xMin' => 72,
        'xMax' => 100,
        'yMin' => 46,
        'yMax' => 72,
      ],
      [
        'color' => 'hsl(136, 53%, 33%)',
        'label' => 9,
        'description' => 'Kinerja di atas ekspektasi dan potensial tinggi',
        'xMin' => 72,
        'xMax' => 100,
        'yMin' => 72,
        'yMax' => 100,
      ],
    ]);
  }

  public static function getSpecificLabels()
  {
    return collect([
      [
        'name' => 'Sangat Baik',
        'value' => 45,
      ],
      [
        'name' => 'Baik',
        'value' => 35,
      ],
      [
        'name' => 'Sedang/Butuh Perbaikan',
        'value' => 25,
      ],
      [
        'name' => 'Kurang',
        'value' => 15,
      ],
      [
        'name' => 'Sangat Kurang',
        'value' => 5,
      ],
    ]);
  }

  public function specificLabel(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $label = $this
          ->getSpecificLabels()
          ->filter(fn($eval) => $eval['value'] <= $attributes['specific'])
          ->first();

        return @$label['name'];
      },
    );
  }

  public function genericValue(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $value = $attributes['innovation'] + $attributes['organizational'] + $attributes['extra'] + $attributes['performance_preference'];

        return $value;
      },
    );
  }

  public static function getPotentialLabels()
  {
    return collect([
      [
        'name' => 'Tinggi',
        'min_value' => 72,
      ],
      [
        'name' => 'Sedang',
        'min_value' => 46,
      ],
      [
        'name' => 'Rendah',
        'min_value' => 0,
      ],
    ]);
  }

  public function potentialLabel(): Attribute
  {
    return Attribute::make(
      get: function () {
        $label = $this
          ->getPotentialLabels()
          ->filter(fn($eval) => $eval['min_value'] <= $this->potential_value)
          ->first();

        return @$label['name'];
      },
    );
  }

  public function getPerformanceValue(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $value = $attributes['specific'] + $this->generic_value;

        return $value;
      }
    );
  }

  public function competencies(): HasMany
  {
    return $this->hasMany(Competency::class, 'nip', 'nip')->orderBy('code');
  }

  public function getUpperCompetency(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $competencies = $this->competencies;

        $sum_value = $competencies->sum('value');
        $sum_skj = $competencies->sum('skj');
        $sum_target = $sum_skj + 9;

        return round($sum_value / $sum_target, 2);
      }
    );
  }

  public function calcUpperCompetency($echelon_code, $position_name, $competencies)
  {
    $echelon_index = (int) str($echelon_code)->charAt(0);
    if ($echelon_index == 9) {
      $prev_inc = 1;

      $position_name = str($position_name)->lower();

      if ($position_name->contains('ahli muda')) {
        $prev_inc = 2;
      } else if ($position_name->contains('ahli madya')) {
        $prev_inc = 3;
      } else if ($position_name->contains('ahli utama')) {
        $prev_inc = 4;
      }

      $new_inc = $prev_inc + 1;
    } else {
      $prev_inc = $echelon_index;
      $new_inc = $prev_inc + 1;
    }

    $total_nilai_target_kompetensi = $competencies->sum('value');


    $competency = round($total_nilai_target_kompetensi / ($new_inc * 9) * 30, 2);

    return $competency;
  }

  public function competencyPercentage(): Attribute
  {
    return Attribute::make(
      get: function () {
        return $this->get_upper_competency * 30;
      }
    );
  }

  public function potentialPercentage(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $potential = round($attributes['potential'] * 0.2, 2);

        return $potential;
      }
    );
  }

  public function trackRecordValue(): Attribute
  {
    return Attribute::make(
      get: function () {
        $track_record_evaluation = $this->employee->getTrackRecordEvaluation();
        return $track_record_evaluation['percentage'];
      }
    );
  }

  private function calculateQualificationValue(Employee $employee)
  {
    $evaluation = [
      'Dibawah_Ekspektasi' => 25,
      'Sesuai_Ekspektasi' => 70,
      'Diatas_Ekspektasi' => 100,
    ];

    if (!$employee->echelon_code) return $evaluation['Sesuai_Ekspektasi'];
    if ($employee->echelon_code === 99) return $evaluation['Sesuai_Ekspektasi'];

    $echelon = $employee->echelonDetail;
    if ($echelon->standard_value < $employee->rank_code) return $evaluation['Diatas_Ekspektasi'];
    if ($echelon->standard_value == $employee->rank_code) return $evaluation['Sesuai_Ekspektasi'];

    return $evaluation['Dibawah_Ekspektasi'];
  }

  public static function getDisciplineList()
  {
    return collect([
      [
        'description' => 'Pemenuhan jam kerja 90 sd 100%',
        'min_attendance_percentage' => 90,
        'max_attendance_percentage' => 100,
        'value' => 100,
      ],
      [
        'description' => 'Pemenuhan jam kerja 80 sd 89%',
        'min_attendance_percentage' => 80,
        'max_attendance_percentage' => 89.9,
        'value' => 80,
      ],
      [
        'description' => 'Pemenuhan jam kerja 70 sd 79%',
        'min_attendance_percentage' => 70,
        'max_attendance_percentage' => 79.9,
        'value' => 60
      ],
      [
        'description' => 'Pemenuhan jam kerja 60 sd 69%',
        'min_attendance_percentage' => 60,
        'max_attendance_percentage' => 69.9,
        'value' => 40,
      ],
      [
        'description' => 'Pemenuhan jam kerja < 60%',
        'min_attendance_percentage' => 0,
        'max_attendance_percentage' => 59.9,
        'value' => 20,
      ],
    ]);
  }

  public function disciplineValue(): Attribute
  {
    return Attribute::make(
      get: function () {
        $attendance_value = $this->employee->attendance?->summary;
        $discipline = self::getDisciplineList()
          ->filter(fn($d) => $d['max_attendance_percentage'] >= $attendance_value)->first();

        if (!$discipline) return 0;

        return $discipline['value'];
      }
    );
  }

  public function getOtherValue(): Attribute
  {
    return Attribute::make(
      get: function () {
        $other_evaluation = $this->employee->getOtherConsiderationEvaluation();
        return @$other_evaluation['percentage'] ?? 0;
      }
    );
  }

  public function getPotentialValue(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $value = $this->potential_percentage
          + $this->competency_percentage
          + $this->track_record_value
          + $this->get_other_value
          + @$attributes['potential_preference'];

        return $value;
      }
    );
  }

  public function syncPerformanceValue()
  {
    $this->update([
      'performance_value' => $this->get_performance_value,
    ]);
  }

  public function syncPotentialValue()
  {
    $this->update([
      'potential_value' => $this->get_potential_value,
      'other' => $this->get_other_value,
    ]);
  }

  public function syncUpperCompetencyValue()
  {
    $this->update([
      'upper_competency' => $this->competency_percentage,
    ]);
  }

  public function syncAllValue()
  {
    $performance_value = $this->get_performance_value;
    $potential_value = $this->get_potential_value;
    $box = TalentPoolBox::query()
      ->orderByDesc('id')
      ->where('min_performance_value', '<', $performance_value)
      ->where('min_potential_value', '<', $potential_value)
      ->first();

    $this->update([
      'potential_value' => $potential_value,
      'other' => $this->get_other_value,
      'performance_value' => $performance_value,
      'box_id' => $box?->id,
    ]);
  }

  public function performanceLabel(): Attribute
  {
    return Attribute::make(
      get: function () {
        $value = $this->performance_value;

        if ($value > 72) {
          return 'Diatas Ekspektasi';
        } else if ($value > 46) {
          return 'Sesuai Ekspektasi';
        } else {
          return 'Dibawah Ekspektasi';
        }
      }
    );
  }

  public function box(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $box = $this->getBoxs()->reverse()->values()->filter(fn($box) =>  $this->potential_value > $box['xMin'] && $this->performance_value > $box['yMin'])->first();
        return @$box;
      }
    );
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'nip', 'nip');
  }

  public function employeePositions(): HasMany
  {
    return $this->hasMany(EmployeePosition::class, 'nip', 'nip');
  }

  public function employeeAttendances(): HasMany
  {
    return $this->hasMany(AttendancePercentage::class, 'nip', 'nip');
  }
}
