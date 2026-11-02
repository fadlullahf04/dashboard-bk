<?php

namespace Database\Factories;

use App\Enums\CaseCategory;
use App\Enums\CaseSeverity;
use App\Enums\CaseStatus;
use App\Models\CounselingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<CounselingRecord>
 */
class CounselingRecordFactory extends Factory
{
    protected $model = CounselingRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = CaseCategory::cases();
        $category = $this->faker->randomElement($categories);

        $incidentDate = Carbon::instance($this->faker->dateTimeBetween('-2 months', 'now'));
        $counselingDate = (clone $incidentDate)->addDays($this->faker->numberBetween(0, 4));

        $classes = ['X-MIPA-1', 'X-MIPA-2', 'X-IPS-1', 'XI-MIPA-1', 'XI-MIPA-2', 'XI-IPS-1', 'XII-MIPA-1', 'XII-IPS-1'];
        $counselors = ['Dra. Hj. Siti Nurhaliza, M.Pd.', 'Budi Santoso, S.Pd., Kons.', 'Rina Kartika, S.Psi.'];

        return [
            'student_name' => $this->faker->name(),
            'student_id_number' => (string) $this->faker->unique()->numerify('2026####'),
            'student_class' => $this->faker->randomElement($classes),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'homeroom_teacher' => $this->faker->name().', S.Pd.',
            'parent_contact' => '0812-'.$this->faker->numerify('####-####'),
            'case_category' => $category->value,
            'case_title' => 'Kasus '.$category->value,
            'problem_description' => $this->faker->paragraph(2),
            'severity' => $this->faker->randomElement(CaseSeverity::cases())->value,
            'incident_date' => $incidentDate->format('Y-m-d'),
            'handling_action' => $this->faker->paragraph(2),
            'follow_up' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(CaseStatus::cases())->value,
            'counseling_date' => $counselingDate->format('Y-m-d'),
            'counseling_summary' => $this->faker->paragraph(2),
            'counselor_name' => $this->faker->randomElement($counselors),
        ];
    }
}

