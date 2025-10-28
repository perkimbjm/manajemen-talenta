<?php

namespace App\Models;

use App\Traits\Sortable;
use App\Traits\Filterable;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
  use HasFactory;
  use HasUuids;
  use Filterable, Sortable;

  protected $fillable = [
    'name',
    'nip',
    'front_title',
    'back_title',
    'position_name',
    'position_rank',
    'position_type',
    'echelon',
    'order',
    'rank',
    'unit_code',
    'work_unit',
    'education_name',
    'education_level',
    'rank_code',
    'echelon_code',
  ];

  protected $searchables = [
    'name',
    'nip',
  ];

  protected $appends = [
    'full_name'
  ];

  protected static function booted(): void
  {
    static::addGlobalScope('encrypted_nip', function (Builder $builder) {
      $builder->select('*');
      $builder->selectRaw('MD5(nip+"$") as encrypted_nip');
    });
  }

  public function getFinalEvaluation(float $value)
  {
    if ($value > 72) {
      return 'Diatas Ekspektasi';
    } else if ($value > 46) {
      return 'Sesuai Ekspektasi';
    } else {
      return 'Dibawah Ekspektasi';
    }
  }

  public function fullName(): Attribute
  {
    return Attribute::make(
      get: function ($_, $attributes) {
        $back_title = preg_replace('/\s+/', '', $attributes['back_title']);
        $front_title = $attributes['front_title'] ? $attributes['front_title'] . ' ' : '';
        $name = $attributes['name'];
        $back_title = @$back_title ? ', ' . $back_title : '';

        return $front_title . $name . $back_title;
      }
    );
  }

  public function performance(): HasOne
  {
    return $this->hasOne(PerformanceManagement::class, 'nip', 'nip');
  }

  public function feedbacks(): HasMany
  {
    return $this->hasMany(Feedback::class, 'nip', 'nip')->chaperone();
  }

  public function unit(): BelongsTo
  {
    return $this->belongsTo(Unit::class, 'unit_code', 'code');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function innovations(): HasMany
  {
    return $this->hasMany(Innovation::class, 'nip', 'nip')->chaperone();
  }

  public function organizations(): HasMany
  {
    return $this->hasMany(Organization::class, 'nip', 'nip')->chaperone();
  }

  public function supportingTasks(): HasMany
  {
    return $this->hasMany(SupportingTask::class, 'nip', 'nip');
  }

  public function assessments(): HasMany
  {
    return $this->hasMany(Assessment::class, 'nip', 'nip')->chaperone();
  }

  public function assessment(): HasOne
  {
    $year = session('year', date('Y'));
    return $this->assessments()->one()->ofMany([
      'created_at' => 'max',
    ], fn($q) => $q->where('year', $year));
  }

  public function educationLevel(): BelongsTo
  {
    return $this->belongsTo(EducationLevel::class, 'education_level');
  }

  public function echelonDetail(): BelongsTo
  {
    return $this->belongsTo(Echelon::class, 'echelon_code');
  }

  public function rankDetail(): BelongsTo
  {
    return $this->belongsTo(Rank::class, 'rank_code');
  }

  public function attendance(): HasOne
  {
    return $this->hasOne(AttendancePercentage::class, 'nip', 'nip');
  }

  public function positions(): HasMany
  {
    return $this->hasMany(EmployeePosition::class, 'nip', 'nip')->chaperone();
  }

  public function punishment(): HasOne
  {
    return $this->hasOne(DisciplinaryPunishment::class, 'nip', 'nip')->chaperone();
  }

  public function lesson_hours(): HasMany
  {
    return $this->hasMany(LessonHour::class, 'nip', 'nip')->chaperone();
  }

  public function diklats(): HasMany
  {
    return $this->hasMany(Diklat::class, 'nip', 'nip')->chaperone();
  }

  public function awards(): HasMany
  {
    return $this->hasMany(Award::class, 'nip', 'nip')->chaperone();
  }

  public function sertificates(): HasMany
  {
    return $this->hasMany(Sertificate::class, 'nip', 'nip')->chaperone();
  }

  public function experiences(): HasMany
  {
    return $this->hasMany(Experience::class, 'nip', 'nip')->chaperone();
  }

  public function getDiklatValue()
  {
    $evaluations = [
      100 => 'Sudah mengikuti Diklat Kepemimpinan/Fungsional sesuai dengan persyaratan jabatan target',
      50 => 'Sedang   mengikuti Diklat Kepemimpinan/Fungsional sesuai dengan persyaratan jabatan target',
      30 => 'Sudah  mengikuti Diklat Kepemimpinan/Fungsional sesuai dengan jabatan sekarang',
      20 => 'Sedang   mengikuti Diklat Kepemimpinan/Fungsional sesuai dengan jabatan sekarang',
      0 => 'Belum ada diklat',
    ];

    $value = 0;

    if ($this->echelon_code == 99) {
      $diklats = $this->diklats;

      if ($diklats->where('status', 1)->count() > 0) return [
        'value' => 30,
        'label' => $evaluations[30]
      ];

      if ($diklats->where('status', 0)->count() > 0) return [
        'value' => 20,
        'label' => $evaluations[20]
      ];

      return [
        'value' => 0,
        'label' => $evaluations[0]
      ];
    }

    if (!$this->echelonDetail?->order) return [
      'value' => $value,
      'label' => $evaluations[$value]
    ];

    $diklats = $this->diklats()->where('rank', '<=', $this->echelonDetail->order)->get();

    if ($diklats->count() === 0) return [
      'value' => $value,
      'label' => $evaluations[$value]
    ];

    $upper_diklats = $diklats->filter(fn($diklat) => $diklat->rank < $this->echelonDetail->order);
    $current_diklats = $diklats->where('rank', $this->echelonDetail->order);
    if ($upper_diklats->count() > 0) {

      if ($upper_diklats->where('status', 1)->count() > 0) return [
        'value' => 100,
        'label' => $evaluations[100]
      ];

      return [
        'value' => 50,
        'label' => $evaluations[50]
      ];
    }

    if ($current_diklats->where('status', 1)->count() > 0) return [
      'value' => 30,
      'label' => $evaluations[30]
    ];

    return [
      'value' => 20,
      'label' => $evaluations[20]
    ];
  }

  public function getLessonHourEvaluation()
  {
    $evaluations = collect([
      [
        'description' => 'Mengikuti pengembangan kompetensi di atas 41 (empat puluh satu) Jam Pelajaran (JP) per tahun dalam 2 tahun terakhir',
        'min_hours' => 41,
        'value' => 100,
      ],
      [
        'description' => 'Mengikuti pengembangan kompetensi 31 (tiga puluh satu) s.d. 40 (empat puluh) Jam Pelajaran (JP) per tahun dalam 2 tahun terakhir',
        'min_hours' => 30,
        'value' => 80,
      ],
      [
        'description' => 'Mengikuti pengembangan kompetensi 21 (dua puluh satu) s.d. 30 (tiga puluh) Jam Pelajaran (JP) per tahun dalam 2 tahun terakhir',
        'min_hours' => 41,
        'value' => 70,
      ],
      [
        'description' => 'Mengikuti pengembangan kompetensi 20 (dua puluh) Jam Pelajaran (JP) per tahun dalam 2 tahun terakhir',
        'min_hours' => 20,
        'value' => 60,
      ],
      [
        'description' => 'Mengikuti pengembangan kompetensi kurang dari 20 (dua puluh) Jam Pelajaran (JP) per tahun dalam 2 tahun terakhir',
        'min_hours' => 0,
        'value' => 20,
      ],
    ]);

    $lesson_hours = $this->lesson_hours()->where('year', '<=', date('Y'))->where('year', '>=', date('Y') - 2);
    $total_hours = $lesson_hours->sum('total_hours');

    $evaluation = $evaluations->filter(fn($eval) => $eval['min_hours'] <= $total_hours)->first();

    return $evaluation;
  }

  public function getMoralityEvaluation()
  {
    $evaluations = collect([
      [
        'description' => 'Tidak pernah dijatuhi hukuman disiplin dalam 3 tahun terakhir',
        'min_year' => 3,
        'value' => 20,
      ],
      [
        'description' => 'Tidak pernah dijatuhi hukuman disiplin dalam 6 tahun terakhir',
        'min_year' => 6,
        'value' => 40,
      ],
      [
        'description' => 'Tidak pernah dijatuhi hukuman disiplin dalam 9 tahun terakhir',
        'min_year' => 9,
        'value' => 60,
      ],
      [
        'description' => 'Tidak pernah dijatuhi hukuman disiplin dalam 12 tahun terakhir',
        'min_year' => 12,
        'value' => 80,
      ],
      [
        'description' => 'Tidak pernah dijatuhi hukuman disiplin dalam 15 tahun terakhir',
        'min_year' => 15,
        'value' => 100,
      ],
    ]);

    $punishments = $this->punishment()->orderByDesc('end_date')->get();

    $max_year = 15;
    if ($punishments->count() > 0) {
      $punishment = $punishments->first();
      $max_year = date('Y') - $punishment->year;
    }

    $evaluation = $evaluations->filter(fn($eval) => $eval['min_year'] >= $max_year)->first();

    return $evaluation;
  }

  public function getQualificationValue()
  {
    $evaluation = [
      'Dibawah_Ekspektasi' => 25,
      'Sesuai_Ekspektasi' => 70,
      'Diatas_Ekspektasi' => 100,
    ];

    if (!$this->echelon_code) return $evaluation['Sesuai_Ekspektasi'];
    if ($this->echelon_code === 99) return $evaluation['Sesuai_Ekspektasi'];

    $echelon = $this->echelonDetail;
    if ($echelon->standard_value < $this->rank_code) return $evaluation['Diatas_Ekspektasi'];
    if ($echelon->standard_value == $this->rank_code) return $evaluation['Sesuai_Ekspektasi'];

    return $evaluation['Dibawah_Ekspektasi'];
  }

  public function getExperienceEvaluation()
  {
    $evaluations = Assessment::getExperienceEvaluations();

    if ($this->positions()->count() === 0) return $evaluations->last();

    $experience_count = $this->positions()->count();

    $experience_evaluation = $evaluations->filter(fn($ex) => $experience_count >= $ex['min_count'])->first();

    return $experience_evaluation;
  }

  public function getAwardEvaluation()
  {
    $evaluations = Award::getTypes();
    $award = $this->awards()->where('status', 2)->orderByDesc('value')->first();

    if (!$award) return [
      'description' => 'Belum ada penghargaan',
      'type' => 'Belum Ada',
      'value' => 0,
    ];

    $evaluation = $evaluations->where('name', $award->type)->first();

    return $evaluation;
  }

  public function getDisciplineEvaluation()
  {
    $attendance_value = $this->attendance?->summary;
    $discipline = Assessment::getDisciplineList()
      ->filter(fn($d) => $d['max_attendance_percentage'] >= $attendance_value)->first();

    if (!$discipline) return Assessment::getDisciplineList()->last();

    return $discipline;
  }


  public function getSertificateEvaluation()
  {
    $evaluations = Sertificate::getTypes();
    $sertificate = $this->sertificates()->where('status', 2)->orderByDesc('value')->first();

    if (!$sertificate) return [
      'description' => 'Belum ada sertifikat',
      'type' => 'Belum Ada',
      'value' => 0,
    ];

    $evaluation = $evaluations->where('name', $sertificate->type)->first();

    return $evaluation;
  }

  public function getOrgExperienceEvaluation()
  {
    $evaluations = Experience::getTypes();
    $experience = $this->experiences()->where('status', 2)->orderByDesc('value')->first();

    if (!$experience) return [
      'description' => 'Belum ada sertifikat',
      'type' => 'Belum Ada',
      'value' => 0,
    ];

    $evaluation = $evaluations->where('name', $experience->type)->first();

    return $evaluation;
  }

  public function getOtherConsiderationEvaluation()
  {
    $org_experience = $this->getOrgExperienceEvaluation();
    $sertificate = $this->getSertificateEvaluation();
    $discipline = $this->getDisciplineEvaluation();

    $total = $org_experience['value'] + $sertificate['value'] + $discipline['value'];

    $weight = 0.15;
    $percentage = $total / 3 * $weight;

    return compact('total', 'percentage', 'weight');
  }

  public function getTrackRecordEvaluation()
  {
    $education_value = @$this->educationLevel?->standard_value;
    $qualification_value = $this->getQualificationValue();
    $experience_evaluation = $this->getExperienceEvaluation();
    $diklat_evaluation = @$this->getDiklatValue();
    $award_evaluation = $this->getAwardEvaluation();
    $morality_evaluation = $this->getMoralityEvaluation();
    $lesson_hour_evaluation = $this->getLessonHourEvaluation();

    $total = $education_value
      + $qualification_value
      + @$experience_evaluation['value']
      + @$diklat_evaluation['value'];

    $total += @$award_evaluation['value'];
    $total += @$morality_evaluation['value'];
    $total += @$lesson_hour_evaluation['value'];

    $weight = 0.35;
    $percentage = $total / 7 * $weight;

    return compact('total', 'percentage', 'weight');
  }

  public function syncProfileFromSimASN()
  {
    $employee = Employee::query()
      ->select(['id', 'nip', 'unit_code'])
      ->selectRaw("MD5(nip+'$') as encrypted_nip")
      ->where('nip', $this->nip)
      ->first();

    $data = $this->getProfileFromSimASN($employee);
    $simasn_unit_mappings = Mapping::where('group', 'SKPD SIM-ASN')->get();
    if (!$data) return;

    $unit_mapping = $simasn_unit_mappings->where('prev_id', $data['kd_skpd'])->first();
    if (!$unit_mapping) {
      if (!$employee->unit_code && !in_array($data['kd_skpd'], ['IDK0000', 'IDK9999'])) {
        throw new \Exception("Unit Mapping not found", 1);
      }
    }

    $employee->update([
      'name' => $data['nm_pegawai'],
      'position_type' => $data['nm_jnsjab'] ?? '-',
      'position_name' => $data['ket_jabatan'] ?? '-',
      'front_title' => $data['glr_dpn'],
      'back_title' => $data['glr_blk'],
      'echelon' => $data['nm_eselon'],
      'order' => $data['gol_pangkat'],
      'rank' => $data['nm_pangkat'],
      'rank_code' => $data['kd_pangkat'],
      'echelon_code' => $data['kd_eselon'],
      'education_level' => $data['kd_pendidikan'] ? (int) $data['kd_pendidikan'] : null,
      'education_name' => $data['nm_pendidikan'],
      'work_unit' => $data['nm_unitkerja'],
      'unit_code' => $unit_mapping?->current_id,
    ]);
  }

  public static function getProfileFromSimASN(Employee $employee)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/pro/{$employee->encrypted_nip}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'][0];
  }

  public function syncDiklatFromSimASN()
  {
    $dataset = $this->getDiklatFromSimASN();
    if (!$dataset) return;
    if (@$dataset['error']) {
      return;
    }

    $ranks = [
      '1' => null,
      '2' => 4,
      '3' => 3,
      '4' => 2,
      '5' => 1,
    ];

    foreach ($dataset as $data) {
      $type = 'Pim';

      $this->diklats()->updateOrCreate([
        'type' => $type,
        'year' => $data['tahun'],
        'code' => $data['latihanStrukturalId'],
      ], [
        'rank' => $ranks[$data['latihanStrukturalId']],
        'name' => $data['latihanStrukturalNama'],
        'date' => $data['tanggal'],
        'letter_number' => $data['nomor'],
        'status' => 1,
      ]);
    }
  }

  public function getDiklatFromSimASN()
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/diklat/{$this->encrypted_nip}";
    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'];
  }
}
