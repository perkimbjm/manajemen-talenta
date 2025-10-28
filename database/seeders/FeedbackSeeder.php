<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class FeedbackSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $employees = Employee::query()
      ->select(['id', 'nip', 'name'])
      ->get();

    if ($employees->isEmpty()) {
      info('Tidak ada pegawai yang perlu diberikan umpan balik.');
      return;
    }

    $year = (int) date('Y');

    progress(
      label: 'Membangun data umpan balik 360°',
      steps: $employees,
      callback: function (Employee $employee) use ($year) {
        $superior_raters = 1;
        $peer_raters = 3;
        $subordinate_raters = 4;

        $superior_value = $this->scoreFromSeed($employee->nip . '_superior');
        $peer_value = $this->scoreFromSeed($employee->nip . '_peer', 55, 95);
        $subordinate_value = $this->scoreFromSeed($employee->nip . '_subordinate', 50, 92);

        $specific_value = $this->calculateAggregate([
          ['score' => $superior_value, 'raters' => $superior_raters],
          ['score' => $peer_value, 'raters' => $peer_raters],
          ['score' => $subordinate_value, 'raters' => $subordinate_raters],
        ]);

        Feedback::updateOrCreate(
          [
            'nip' => $employee->nip,
            'year' => $year,
          ],
          [
            'superior_raters' => $superior_raters,
            'peer_raters' => $peer_raters,
            'subordinate_raters' => $subordinate_raters,
            'superior_value' => $superior_value,
            'peer_value' => $peer_value,
            'subordinate_value' => $subordinate_value,
            'specific_value' => $specific_value,
            'status' => 2,
            'notes' => 'Data umpan balik otomatis untuk kebutuhan pengembangan aplikasi.',
          ]
        );
      }
    );
  }

  private function scoreFromSeed(string $seed, int $min = 60, int $max = 100): float
  {
    $hash = crc32($seed);
    $range = max(1, ($max - $min) * 100);

    return round($min + ($hash % $range) / 100, 2);
  }

  private function calculateAggregate(array $groups): float
  {
    $prepared = collect($groups)
      ->filter(fn($group) => $group['score'] > 0)
      ->map(function ($group) {
        $weight = $group['raters'] ?? 0;
        $weight = $weight > 0 ? $weight : 1;

        return [
          'score' => (float) $group['score'],
          'weight' => (int) $weight,
        ];
      });

    if ($prepared->isEmpty()) {
      return 0.0;
    }

    $total_weight = $prepared->sum('weight');
    if ($total_weight === 0) {
      return 0.0;
    }

    $weighted = $prepared->reduce(
      fn($carry, $group) => $carry + ($group['score'] * $group['weight']),
      0.0
    );

    return round($weighted / $total_weight, 2);
  }
}
