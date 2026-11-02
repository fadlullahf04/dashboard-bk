<?php

namespace Tests\Feature;

use App\Enums\CaseCategory;
use App\Enums\CaseSeverity;
use App\Enums\CaseStatus;
use App\Enums\UserRole;
use App\Models\CounselingRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselingRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_counseling_records_and_monthly_summary(): void
    {
        $user = User::factory()->create(['role' => UserRole::SuperAdmin->value]);

        CounselingRecord::factory()->create([
            'student_name' => 'Aditya Pratama Putra',
            'case_category' => CaseCategory::Kehadiran->value,
            'incident_date' => '2026-11-01',
            'counseling_date' => '2026-11-02',
        ]);

        $this->actingAs($user)
            ->get(route('bk.dashboard'))
            ->assertStatus(200)
            ->assertSee('Aditya Pratama Putra');

        $response = $this->actingAs($user)->get(route('bk.index'));

        $response->assertStatus(200);
        $response->assertSee('Aditya Pratama Putra');
        $response->assertSee(CaseCategory::Kehadiran->value);
    }

    public function test_can_create_new_counseling_record(): void
    {
        $psikologi = User::factory()->create(['role' => UserRole::Psikologi->value]);

        $payload = [
            'student_name' => 'Siti Aisyah',
            'student_id_number' => '25109901',
            'student_class' => 'X-MIPA-1',
            'gender' => 'P',
            'homeroom_teacher' => 'Budi Hartono, S.Pd.',
            'parent_contact' => '0812-1111-2222',
            'case_category' => CaseCategory::Akademik->value,
            'case_title' => 'Kesulitan adaptasi materi Matematika Peminatan',
            'problem_description' => 'Siswa mengalami kesulitan memahami konsep dasar trigonometri.',
            'severity' => CaseSeverity::Medium->value,
            'incident_date' => '2026-11-01',
            'handling_action' => 'Konseling belajar individual dan pemberian modul pendamping.',
            'follow_up' => 'Koordinasi dengan guru mata pelajaran dan evaluasi pekan depan.',
            'status' => CaseStatus::Monitoring->value,
            'counseling_date' => '2026-11-02',
            'counseling_summary' => 'Siswa menyusun jadwal belajar harian dan siap mengikuti klinik akademik.',
            'counselor_name' => 'Dra. Hj. Siti Nurhaliza, M.Pd.',
        ];

        $response = $this->actingAs($psikologi)->post(route('bk.store'), $payload);

        $record = CounselingRecord::where('student_id_number', '25109901')->first();
        $this->assertNotNull($record);
        $response->assertRedirect(route('bk.show', $record));

        $this->assertDatabaseHas('counseling_records', [
            'student_name' => 'Siti Aisyah',
            'case_category' => CaseCategory::Akademik->value,
            'handling_action' => 'Konseling belajar individual dan pemberian modul pendamping.',
            'counseling_summary' => 'Siswa menyusun jadwal belajar harian dan siap mengikuti klinik akademik.',
        ]);
    }

    public function test_can_search_by_name_category_date_and_month(): void
    {
        $user = User::factory()->create(['role' => UserRole::Staff->value]);

        CounselingRecord::factory()->create([
            'student_name' => 'Bima Sakti',
            'case_category' => CaseCategory::Kedisiplinan->value,
            'incident_date' => '2026-11-02',
            'counseling_date' => '2026-11-02',
        ]);

        CounselingRecord::factory()->create([
            'student_name' => 'Citra Kirana',
            'case_category' => CaseCategory::Karier->value,
            'incident_date' => '2026-10-15',
            'counseling_date' => '2026-10-16',
        ]);

        // Search by name
        $this->actingAs($user)
            ->get(route('bk.index', ['search' => 'Bima']))
            ->assertOk()
            ->assertSee('Bima Sakti')
            ->assertDontSee('Citra Kirana');

        // Filter by category
        $this->actingAs($user)
            ->get(route('bk.index', ['category' => CaseCategory::Karier->value]))
            ->assertOk()
            ->assertSee('Citra Kirana')
            ->assertDontSee('Bima Sakti');

        // Filter by specific date
        $this->actingAs($user)
            ->get(route('bk.index', ['date' => '2026-11-02']))
            ->assertOk()
            ->assertSee('Bima Sakti')
            ->assertDontSee('Citra Kirana');

        // Filter by month
        $this->actingAs($user)
            ->get(route('bk.index', ['month' => '2026-10']))
            ->assertOk()
            ->assertSee('Citra Kirana')
            ->assertDontSee('Bima Sakti');
    }

    public function test_monthly_report_calculates_total_cases_and_problem_variety(): void
    {
        $atasan = User::factory()->create(['role' => UserRole::Atasan->value]);

        CounselingRecord::factory()->count(2)->create([
            'case_category' => CaseCategory::Kedisiplinan->value,
            'incident_date' => '2026-11-01',
            'counseling_date' => '2026-11-02',
            'status' => CaseStatus::Resolved->value,
        ]);

        CounselingRecord::factory()->create([
            'case_category' => CaseCategory::SosialPerundungan->value,
            'incident_date' => '2026-11-02',
            'counseling_date' => '2026-11-02',
            'status' => CaseStatus::Monitoring->value,
        ]);

        CounselingRecord::factory()->create([
            'student_name' => 'Siswa Bulan Lalu',
            'case_category' => CaseCategory::Akademik->value,
            'incident_date' => '2026-10-10',
            'counseling_date' => '2026-10-11',
        ]);

        $response = $this->actingAs($atasan)->get(route('bk.report', ['month' => '2026-11']));

        $response->assertOk();
        $response->assertViewHas('reportSummary', function (array $summary): bool {
            return $summary['total_cases'] === 3
                && $summary['active_categories_count'] === 2
                && $summary['resolved_count'] === 2
                && $summary['monitoring_count'] === 1;
        });

        $csvResponse = $this->actingAs($atasan)->get(route('bk.report.csv', ['month' => '2026-11']));
        $csvResponse->assertOk();
    }

    public function test_role_based_access_control_matches_requirements(): void
    {
        $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin->value]);
        $psikologi = User::factory()->create(['role' => UserRole::Psikologi->value]);
        $staff = User::factory()->create(['role' => UserRole::Staff->value]);
        $atasan = User::factory()->create(['role' => UserRole::Atasan->value]);

        // 1. Super Admin can access everything including User Management
        $this->actingAs($superAdmin)->get(route('bk.identitas'))->assertOk();
        $this->actingAs($superAdmin)->get(route('bk.index'))->assertOk();
        $this->actingAs($superAdmin)->get(route('bk.create'))->assertOk();
        $this->actingAs($superAdmin)->get(route('bk.report'))->assertOk();
        $this->actingAs($superAdmin)->get(route('users.index'))->assertOk();

        // 2. Psikologi can access everything EXCEPT User Management
        $this->actingAs($psikologi)->get(route('bk.identitas'))->assertOk();
        $this->actingAs($psikologi)->get(route('bk.index'))->assertOk();
        $this->actingAs($psikologi)->get(route('bk.create'))->assertOk();
        $this->actingAs($psikologi)->get(route('bk.report'))->assertOk();
        $this->actingAs($psikologi)->get(route('users.index'))->assertForbidden();

        // 3. Staff can ONLY access Identitas and Rekap
        $this->actingAs($staff)->get(route('bk.identitas'))->assertOk();
        $this->actingAs($staff)->get(route('bk.index'))->assertOk();
        $this->actingAs($staff)->get(route('bk.create'))->assertForbidden();
        $this->actingAs($staff)->get(route('bk.report'))->assertForbidden();
        $this->actingAs($staff)->get(route('users.index'))->assertForbidden();

        // 4. Atasan can ONLY access Rekap and Laporan
        $this->actingAs($atasan)->get(route('bk.index'))->assertOk();
        $this->actingAs($atasan)->get(route('bk.report'))->assertOk();
        $this->actingAs($atasan)->get(route('bk.identitas'))->assertForbidden();
        $this->actingAs($atasan)->get(route('bk.create'))->assertForbidden();
        $this->actingAs($atasan)->get(route('users.index'))->assertForbidden();
    }

    public function test_admin_can_manage_case_categories_and_sync_existing_cases(): void
    {
        $admin = User::factory()->create(['role' => UserRole::SuperAdmin->value]);

        // Create a new category
        $this->actingAs($admin)
            ->post(route('categories.store'), [
                'name' => 'Kesehatan Mental & Emosi',
                'color' => 'teal',
                'description' => 'Konseling kesehatan mental dan manajemen stres siswa',
            ])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('case_categories', [
            'name' => 'Kesehatan Mental & Emosi',
            'color' => 'teal',
        ]);

        // Create a record using the new custom category
        $record = CounselingRecord::factory()->create([
            'case_category' => 'Kesehatan Mental & Emosi',
        ]);

        $category = \App\Models\CaseCategoryItem::where('name', 'Kesehatan Mental & Emosi')->firstOrFail();

        // Rename the category and verify existing record is automatically synchronized
        $this->actingAs($admin)
            ->put(route('categories.update', $category), [
                'name' => 'Psikologis & Kesehatan Mental',
                'color' => 'purple',
                'description' => 'Diperbarui',
            ])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('counseling_records', [
            'id' => $record->id,
            'case_category' => 'Psikologis & Kesehatan Mental',
        ]);
    }
}
