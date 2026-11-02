<?php

namespace App\Http\Controllers;

use App\Enums\CaseSeverity;
use App\Enums\CaseStatus;
use App\Http\Requests\StoreCounselingRecordRequest;
use App\Models\CaseCategoryItem;
use App\Models\CounselingRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CounselingRecordController extends Controller
{
    /**
     * Display the executive BK Dashboard overview.
     */
    public function dashboard(): View
    {
        $now = Carbon::now();
        $activeMonth = $now->format('Y-m');
        $activeMonthLabel = $now->locale('id')->translatedFormat('F Y');
        $allCategories = CaseCategoryItem::allResolved();

        $monthlyQuery = CounselingRecord::query()
            ->whereYear('incident_date', (int) $now->year)
            ->whereMonth('incident_date', (int) $now->month);

        $monthlyTotal = (clone $monthlyQuery)->count();

        $prevMonthDate = (clone $now)->subMonth();
        $prevMonthTotal = CounselingRecord::query()
            ->whereYear('incident_date', (int) $prevMonthDate->year)
            ->whereMonth('incident_date', (int) $prevMonthDate->month)
            ->count();

        $monthlyByCategory = (clone $monthlyQuery)
            ->selectRaw('case_category, count(*) as total')
            ->groupBy('case_category')
            ->pluck('total', 'case_category')
            ->all();

        $categorySummary = [];
        foreach ($allCategories as $category) {
            $count = (int) ($monthlyByCategory[$category->value] ?? 0);
            $categorySummary[] = [
                'enum' => $category,
                'label' => $category->value,
                'count' => $count,
                'percentage' => $monthlyTotal > 0 ? round(($count / $monthlyTotal) * 100, 1) : 0.0,
            ];
        }

        usort($categorySummary, fn (array $a, array $b): int => $b['count'] <=> $a['count']);

        $stats = [
            'total_all' => CounselingRecord::count(),
            'monthly_total' => $monthlyTotal,
            'prev_month_total' => $prevMonthTotal,
            'monthly_diff' => $monthlyTotal - $prevMonthTotal,
            'monthly_open' => (clone $monthlyQuery)->where('status', CaseStatus::Open->value)->count(),
            'monthly_monitoring' => (clone $monthlyQuery)->where('status', CaseStatus::Monitoring->value)->count(),
            'monthly_resolved' => (clone $monthlyQuery)->where('status', CaseStatus::Resolved->value)->count(),
            'active_open_all' => CounselingRecord::whereIn('status', [CaseStatus::Open->value, CaseStatus::Monitoring->value])->count(),
            'distinct_categories_this_month' => count(array_filter($categorySummary, fn (array $item): bool => $item['count'] > 0)),
            'total_categories' => $allCategories->count(),
        ];

        $recentRecords = CounselingRecord::query()
            ->orderByDesc('incident_date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $priorityRecords = CounselingRecord::query()
            ->whereIn('status', [CaseStatus::Open->value, CaseStatus::Monitoring->value])
            ->orderByRaw("CASE WHEN severity = 'Berat' THEN 1 WHEN severity = 'Sedang' THEN 2 ELSE 3 END")
            ->orderByDesc('incident_date')
            ->limit(4)
            ->get();

        return view('bk.dashboard', [
            'stats' => $stats,
            'categorySummary' => $categorySummary,
            'recentRecords' => $recentRecords,
            'priorityRecords' => $priorityRecords,
            'activeMonth' => $activeMonth,
            'activeMonthLabel' => $activeMonthLabel,
        ]);
    }

    /**
     * Display the dedicated Data Identitas Siswa page (Accessible by Super Admin, Psikologi, Staff).
     */
    public function identitasIndex(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $classFilter = $request->string('class')->trim()->toString();

        $query = CounselingRecord::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhere('student_id_number', 'like', "%{$search}%")
                    ->orWhere('homeroom_teacher', 'like', "%{$search}%")
                    ->orWhere('parent_contact', 'like', "%{$search}%");
            });
        }

        if ($classFilter !== '') {
            $query->where('student_class', $classFilter);
        }

        $records = $query
            ->orderBy('student_class')
            ->orderBy('student_name')
            ->paginate(12)
            ->withQueryString();

        $availableClasses = CounselingRecord::query()
            ->select('student_class')
            ->distinct()
            ->orderBy('student_class')
            ->pluck('student_class');

        return view('bk.identitas', [
            'records' => $records,
            'search' => $search,
            'classFilter' => $classFilter,
            'availableClasses' => $availableClasses,
            'categories' => CaseCategoryItem::allResolved(),
        ]);
    }

    /**
     * Store a new student identity registration (Accessible by Super Admin, Psikologi, Staff).
     */
    public function identitasStore(Request $request): RedirectResponse
    {
        $validCategories = CaseCategoryItem::allResolved()->pluck('name')->all();

        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:150'],
            'student_id_number' => ['required', 'string', 'max:50'],
            'student_class' => ['required', 'string', 'max:50'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'homeroom_teacher' => ['nullable', 'string', 'max:150'],
            'parent_contact' => ['nullable', 'string', 'max:150'],
            'case_category' => ['required', 'string', Rule::in($validCategories)],
            'case_title' => ['required', 'string', 'max:255'],
            'incident_date' => ['required', 'date'],
        ]);

        $record = CounselingRecord::create([
            'student_name' => $validated['student_name'],
            'student_id_number' => $validated['student_id_number'],
            'student_class' => $validated['student_class'],
            'gender' => $validated['gender'],
            'homeroom_teacher' => $validated['homeroom_teacher'] ?? null,
            'parent_contact' => $validated['parent_contact'] ?? null,
            'case_category' => $validated['case_category'],
            'case_title' => $validated['case_title'],
            'problem_description' => 'Registrasi identitas awal oleh '.$request->user()?->name.' ('.$validated['case_title'].').',
            'severity' => CaseSeverity::Low->value,
            'incident_date' => $validated['incident_date'],
            'handling_action' => 'Menunggu asesmen & penanganan psikologi/konselor BK.',
            'follow_up' => 'Dijadwalkan sesi konseling lanjutan.',
            'status' => CaseStatus::Open->value,
            'counseling_date' => $validated['incident_date'],
            'counseling_summary' => 'Identitas siswa telah tercatat dan siap ditindaklanjuti oleh konselor/psikologi.',
            'counselor_name' => $request->user()?->name ?? 'Staff Administrasi BK',
        ]);

        return redirect()
            ->route('bk.identitas')
            ->with('success', "Identitas siswa {$record->student_name} (NIS: {$record->student_id_number}) berhasil dicatat.");
    }

    /**
     * Update student identity fields on an existing record (Accessible by Super Admin, Psikologi, Staff).
     */
    public function identitasUpdate(Request $request, CounselingRecord $counselingRecord): RedirectResponse
    {
        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:150'],
            'student_id_number' => ['required', 'string', 'max:50'],
            'student_class' => ['required', 'string', 'max:50'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'homeroom_teacher' => ['nullable', 'string', 'max:150'],
            'parent_contact' => ['nullable', 'string', 'max:150'],
        ]);

        $counselingRecord->update($validated);

        return redirect()
            ->route('bk.identitas')
            ->with('success', "Data identitas siswa {$counselingRecord->student_name} berhasil diperbarui.");
    }

    /**
     * Display the dedicated Rekap Data Kasus & Pencarian page (Accessible by all authenticated roles).
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->trim()->toString(),
            'category' => $request->string('category')->trim()->toString(),
            'date' => $request->string('date')->trim()->toString(),
            'month' => $request->string('month')->trim()->toString(),
            'status' => $request->string('status')->trim()->toString(),
            'severity' => $request->string('severity')->trim()->toString(),
            'class' => $request->string('class')->trim()->toString(),
            'date_field' => $request->string('date_field', 'incident_date')->trim()->toString(),
        ];

        $filteredQuery = CounselingRecord::query()->filter($filters);

        $filterSummary = [
            'total_found' => (clone $filteredQuery)->count(),
            'open_count' => (clone $filteredQuery)->where('status', CaseStatus::Open->value)->count(),
            'monitoring_count' => (clone $filteredQuery)->where('status', CaseStatus::Monitoring->value)->count(),
            'resolved_count' => (clone $filteredQuery)->where('status', CaseStatus::Resolved->value)->count(),
        ];

        $records = $filteredQuery
            ->orderByDesc('incident_date')
            ->orderByDesc('counseling_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $availableClasses = CounselingRecord::query()
            ->select('student_class')
            ->distinct()
            ->orderBy('student_class')
            ->pluck('student_class');

        return view('bk.index', [
            'records' => $records,
            'filters' => $filters,
            'filterSummary' => $filterSummary,
            'categories' => CaseCategoryItem::allResolved(),
            'statuses' => CaseStatus::cases(),
            'severities' => CaseSeverity::cases(),
            'availableClasses' => $availableClasses,
        ]);
    }

    /**
     * Show the form for creating a new counseling record (Super Admin & Psikologi).
     */
    public function create(): View
    {
        return view('bk.create', [
            'categories' => CaseCategoryItem::allResolved(),
            'statuses' => CaseStatus::cases(),
            'severities' => CaseSeverity::cases(),
        ]);
    }

    /**
     * Store a newly created counseling record in storage (Super Admin & Psikologi).
     */
    public function store(StoreCounselingRecordRequest $request): RedirectResponse
    {
        $record = CounselingRecord::create($request->validated());

        return redirect()
            ->route('bk.show', $record)
            ->with('success', "Data kasus & konseling untuk {$record->student_name} ({$record->case_code}) berhasil disimpan.");
    }

    /**
     * Display the specified counseling record (All authenticated roles can view detail rekap).
     */
    public function show(CounselingRecord $counselingRecord): View
    {
        $studentHistory = CounselingRecord::query()
            ->where(function ($query) use ($counselingRecord): void {
                $query->where('student_id_number', $counselingRecord->student_id_number)
                    ->orWhere('student_name', $counselingRecord->student_name);
            })
            ->where('id', '!=', $counselingRecord->id)
            ->orderByDesc('incident_date')
            ->get();

        return view('bk.show', [
            'record' => $counselingRecord,
            'studentHistory' => $studentHistory,
        ]);
    }

    /**
     * Show the form for editing the specified counseling record (Super Admin & Psikologi).
     */
    public function edit(CounselingRecord $counselingRecord): View
    {
        return view('bk.edit', [
            'record' => $counselingRecord,
            'categories' => CaseCategoryItem::allResolved(),
            'statuses' => CaseStatus::cases(),
            'severities' => CaseSeverity::cases(),
        ]);
    }

    /**
     * Update the specified counseling record in storage (Super Admin & Psikologi).
     */
    public function update(StoreCounselingRecordRequest $request, CounselingRecord $counselingRecord): RedirectResponse
    {
        $counselingRecord->update($request->validated());

        return redirect()
            ->route('bk.show', $counselingRecord)
            ->with('success', "Data kasus & konseling {$counselingRecord->student_name} berhasil diperbarui.");
    }

    /**
     * Remove the specified counseling record from storage (Super Admin & Psikologi).
     */
    public function destroy(CounselingRecord $counselingRecord): RedirectResponse
    {
        $studentName = $counselingRecord->student_name;
        $caseCode = $counselingRecord->case_code;
        $counselingRecord->delete();

        return redirect()
            ->route('bk.index')
            ->with('success', "Data kasus {$caseCode} ({$studentName}) berhasil dihapus.");
    }

    /**
     * Display the monthly BK report (Super Admin, Psikologi, Atasan).
     */
    public function monthlyReport(Request $request): View
    {
        $selectedMonth = $request->string('month')->trim()->toString();
        if (empty($selectedMonth) || ! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }

        $dateField = $request->string('date_field', 'incident_date')->trim()->toString() === 'counseling_date'
            ? 'counseling_date'
            : 'incident_date';

        $selectedCategory = $request->string('category')->trim()->toString();
        $allCategories = CaseCategoryItem::allResolved();

        [$year, $month] = explode('-', $selectedMonth);
        $currentDate = Carbon::createFromDate((int) $year, (int) $month, 1);
        $prevDate = (clone $currentDate)->subMonth();

        $baseMonthlyQuery = CounselingRecord::query()
            ->whereYear($dateField, (int) $year)
            ->whereMonth($dateField, (int) $month);

        $allMonthlyRecords = (clone $baseMonthlyQuery)
            ->orderBy($dateField)
            ->orderBy('student_name')
            ->get();

        $filteredMonthlyRecords = $selectedCategory !== ''
            ? $allMonthlyRecords->filter(fn (CounselingRecord $r): bool => $r->case_category->value === $selectedCategory)->values()
            : $allMonthlyRecords;

        $prevMonthTotal = CounselingRecord::query()
            ->whereYear($dateField, (int) $prevDate->year)
            ->whereMonth($dateField, (int) $prevDate->month)
            ->count();

        $totalCases = $allMonthlyRecords->count();
        $diffFromPrev = $totalCases - $prevMonthTotal;

        $varietyBreakdown = [];
        foreach ($allCategories as $category) {
            $categoryRecords = $allMonthlyRecords->filter(
                fn (CounselingRecord $record): bool => $record->case_category->value === $category->value
            );
            $count = $categoryRecords->count();
            $resolvedCount = $categoryRecords->filter(
                fn (CounselingRecord $record): bool => $record->status === CaseStatus::Resolved
            )->count();
            $monitoringCount = $categoryRecords->filter(
                fn (CounselingRecord $record): bool => $record->status === CaseStatus::Monitoring
            )->count();
            $openCount = $categoryRecords->filter(
                fn (CounselingRecord $record): bool => $record->status === CaseStatus::Open
            )->count();

            $varietyBreakdown[] = [
                'enum' => $category,
                'category' => $category->value,
                'count' => $count,
                'percentage' => $totalCases > 0 ? round(($count / $totalCases) * 100, 1) : 0.0,
                'resolved' => $resolvedCount,
                'monitoring' => $monitoringCount,
                'open' => $openCount,
                'sample_titles' => $categoryRecords->pluck('case_title')->take(3)->values()->all(),
            ];
        }

        usort($varietyBreakdown, fn (array $a, array $b): int => $b['count'] <=> $a['count']);

        $activeCategoriesCount = count(array_filter($varietyBreakdown, fn (array $item): bool => $item['count'] > 0));
        $dominantCategory = $totalCases > 0 && ! empty($varietyBreakdown) && $varietyBreakdown[0]['count'] > 0
            ? $varietyBreakdown[0]
            : null;

        $classBreakdown = $allMonthlyRecords
            ->groupBy('student_class')
            ->map(fn ($items, string $className): array => [
                'class' => $className,
                'count' => $items->count(),
                'percentage' => $totalCases > 0 ? round(($items->count() / $totalCases) * 100, 1) : 0.0,
            ])
            ->sortByDesc('count')
            ->values()
            ->all();

        $severityBreakdown = [];
        foreach (CaseSeverity::cases() as $severity) {
            $count = $allMonthlyRecords->filter(fn (CounselingRecord $r): bool => $r->severity === $severity)->count();
            $severityBreakdown[] = [
                'enum' => $severity,
                'label' => $severity->value,
                'count' => $count,
                'percentage' => $totalCases > 0 ? round(($count / $totalCases) * 100, 1) : 0.0,
            ];
        }

        $reportSummary = [
            'total_cases' => $totalCases,
            'prev_month_total' => $prevMonthTotal,
            'diff_from_prev' => $diffFromPrev,
            'unique_students' => $allMonthlyRecords->pluck('student_id_number')->unique()->count(),
            'active_categories_count' => $activeCategoriesCount,
            'total_categories_available' => $allCategories->count(),
            'resolved_count' => $allMonthlyRecords->filter(fn (CounselingRecord $r): bool => $r->status === CaseStatus::Resolved)->count(),
            'monitoring_count' => $allMonthlyRecords->filter(fn (CounselingRecord $r): bool => $r->status === CaseStatus::Monitoring)->count(),
            'open_count' => $allMonthlyRecords->filter(fn (CounselingRecord $r): bool => $r->status === CaseStatus::Open)->count(),
            'dominant_category' => $dominantCategory,
        ];

        return view('bk.report', [
            'selectedMonth' => $selectedMonth,
            'monthLabel' => $currentDate->locale('id')->translatedFormat('F Y'),
            'prevMonthLabel' => $prevDate->locale('id')->translatedFormat('F Y'),
            'dateField' => $dateField,
            'selectedCategory' => $selectedCategory,
            'categories' => $allCategories,
            'reportSummary' => $reportSummary,
            'varietyBreakdown' => $varietyBreakdown,
            'classBreakdown' => $classBreakdown,
            'severityBreakdown' => $severityBreakdown,
            'records' => $filteredMonthlyRecords,
        ]);
    }

    /**
     * Export monthly BK report records as CSV (Super Admin, Psikologi, Atasan).
     */
    public function exportMonthlyCsv(Request $request): StreamedResponse
    {
        $selectedMonth = $request->string('month')->trim()->toString();
        if (empty($selectedMonth) || ! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }

        $dateField = $request->string('date_field', 'incident_date')->trim()->toString() === 'counseling_date'
            ? 'counseling_date'
            : 'incident_date';

        [$year, $month] = explode('-', $selectedMonth);

        $records = CounselingRecord::query()
            ->whereYear($dateField, (int) $year)
            ->whereMonth($dateField, (int) $month)
            ->orderBy($dateField)
            ->get();

        $filename = "Laporan-BK-{$selectedMonth}.csv";

        return response()->streamDownload(function () use ($records): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Kasus',
                'Nama Siswa',
                'NIS/NISN',
                'Kelas',
                'L/P',
                'Wali Kelas',
                'Kontak Orang Tua',
                'Kategori Case',
                'Judul Masalah',
                'Deskripsi Masalah',
                'Urgensi',
                'Tanggal Kejadian',
                'Penanganan',
                'Tindak Lanjut',
                'Tanggal Konseling',
                'Rangkuman Konseling',
                'Guru BK / Konselor',
                'Status',
            ]);

            foreach ($records as $record) {
                fputcsv($handle, [
                    $record->case_code,
                    $record->student_name,
                    $record->student_id_number,
                    $record->student_class,
                    $record->gender,
                    $record->homeroom_teacher,
                    $record->parent_contact,
                    $record->case_category->value,
                    $record->case_title,
                    $record->problem_description,
                    $record->severity->value,
                    $record->incident_date->format('Y-m-d'),
                    $record->handling_action,
                    $record->follow_up,
                    $record->counseling_date->format('Y-m-d'),
                    $record->counseling_summary,
                    $record->counselor_name,
                    $record->status->value,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
