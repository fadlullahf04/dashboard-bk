@extends('layouts.app')

@section('page_title', 'Laporan Bulanan Bimbingan & Konseling')
@section('page_subtitle', 'Rekapitulasi jumlah case dalam 1 bulan dan analisis ragam masalah periode ' . $monthLabel)

@section('content')
<div class="space-y-8">
    <!-- Top Header & Filter Bulan Laporan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-1">
                    <span>Laporan Resmi Unit BK</span>
                    <span>&bull;</span>
                    <span>Rekapitulasi Bulanan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Laporan Bimbingan & Konseling Bulan {{ $monthLabel }}
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Menyajikan analisis jumlah case dalam 1 bulan, peta ragam masalah yang ada, persentase penyelesaian, serta rincian lengkap penanganan dan rangkuman konseling.
                </p>
            </div>

            <!-- Action Buttons (Print & Export CSV) -->
            <div class="flex flex-wrap items-center gap-2.5 no-print">
                <a href="{{ route('bk.report.csv', ['month' => $selectedMonth, 'date_field' => $dateField]) }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Unduh CSV / Excel</span>
                </a>

                <button type="button"
                        onclick="window.print()"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>
                    <span>Cetak Laporan Bulanan</span>
                </button>
            </div>
        </div>

        <!-- Filter Periode Bulan -->
        <form action="{{ route('bk.report') }}" method="GET" class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end no-print">
            <div class="lg:col-span-3">
                <label for="month" class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Bulan & Tahun Laporan</label>
                <input type="month"
                       name="month"
                       id="month"
                       value="{{ $selectedMonth }}"
                       class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="lg:col-span-3">
                <label for="date_field" class="block text-xs font-bold text-slate-700 mb-1.5">Basis Perhitungan Tanggal</label>
                <select name="date_field"
                        id="date_field"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="incident_date" @selected($dateField === 'incident_date')>Berdasarkan Tanggal Kejadian</option>
                    <option value="counseling_date" @selected($dateField === 'counseling_date')>Berdasarkan Tanggal Konseling</option>
                </select>
            </div>

            <div class="lg:col-span-4">
                <label for="category" class="block text-xs font-bold text-slate-700 mb-1.5">Filter Tabel Rincian Berdasarkan Kategori</label>
                <select name="category"
                        id="category"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">-- Tampilkan Semua Kategori Masalah --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>
                            {{ $category->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <button type="submit"
                        class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white hover:bg-slate-800 transition-colors cursor-pointer">
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Section 1: Ringkasan Eksekutif (Jumlah Case dalam 1 Bulan & Ragam Masalah) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Jumlah Case dalam 1 Bulan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Jumlah Case dalam 1 Bulan</span>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">{{ $reportSummary['total_cases'] }}</span>
                <span class="text-sm font-semibold text-slate-500">Kasus</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">
                @if ($reportSummary['diff_from_prev'] > 0)
                    <span class="font-semibold text-rose-600">+{{ $reportSummary['diff_from_prev'] }} kasus</span> dibanding {{ $prevMonthLabel }} ({{ $reportSummary['prev_month_total'] }} kasus)
                @elseif ($reportSummary['diff_from_prev'] < 0)
                    <span class="font-semibold text-emerald-600">{{ $reportSummary['diff_from_prev'] }} kasus</span> dibanding {{ $prevMonthLabel }} ({{ $reportSummary['prev_month_total'] }} kasus)
                @else
                    Sama dengan jumlah kasus bulan {{ $prevMonthLabel }} ({{ $reportSummary['prev_month_total'] }} kasus)
                @endif
            </p>
        </div>

        <!-- Ragam Masalah yang Ada -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-violet-600">Ragam Masalah yang Ada</span>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">{{ $reportSummary['active_categories_count'] }}</span>
                <span class="text-sm font-semibold text-slate-500">dari {{ $reportSummary['total_categories_available'] }} Kategori</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">
                @if ($reportSummary['dominant_category'])
                    Dominan: <strong class="text-slate-800">{{ $reportSummary['dominant_category']['category'] }} ({{ $reportSummary['dominant_category']['count'] }})</strong>
                @else
                    Belum ada kasus tercatat pada bulan ini
                @endif
            </p>
        </div>

        <!-- Siswa Terlayani -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-sky-600">Cakupan Layanan Konseling</span>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">{{ $reportSummary['unique_students'] }}</span>
                <span class="text-sm font-semibold text-slate-500">Siswa Terlayani</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">
                Meliputi <strong class="text-slate-800">{{ count($classBreakdown) }} kelas</strong> pada periode {{ $monthLabel }}
            </p>
        </div>

        <!-- Status Penyelesaian -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Status Tindak Lanjut</span>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-emerald-600">{{ $reportSummary['resolved_count'] }}</span>
                <span class="text-sm font-semibold text-slate-500">Selesai</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">
                <span class="font-semibold text-sky-700">{{ $reportSummary['monitoring_count'] }} Dipantau</span> &bull;
                <span class="font-semibold text-amber-700">{{ $reportSummary['open_count'] }} Terbuka</span>
            </p>
        </div>
    </div>

    <!-- Section 2: Rekapitulasi Ragam Masalah yang Ada (Problem Variety Matrix) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">1. Analisis Ragam Masalah yang Ada ({{ $monthLabel }})</h2>
                    <p class="text-xs text-slate-500">Distribusi jumlah kasus, persentase, dan status penanganan untuk setiap kategori masalah BK</p>
                </div>
                <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg">
                    Total: {{ $reportSummary['total_cases'] }} Case
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3 pl-6 pr-3">Kategori Masalah / Case</th>
                            <th class="px-3 py-3 text-center">Jumlah Case</th>
                            <th class="px-3 py-3 w-44">Proporsi (%)</th>
                            <th class="px-3 py-3 text-center">Status (Selesai / Pantau / Terbuka)</th>
                            <th class="py-3 pl-3 pr-6">Contoh Kasus Bulan Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 bg-white">
                        @foreach ($varietyBreakdown as $row)
                            <tr class="hover:bg-slate-50/70">
                                <td class="py-3.5 pl-6 pr-3">
                                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $row['enum']->badgeClasses() }}">
                                        {{ $row['category'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 text-center">
                                    <span class="text-base font-extrabold {{ $row['count'] > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                        {{ $row['count'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['enum']->barColorClass() }}"
                                                 style="width: {{ max($row['percentage'], $row['count'] > 0 ? 6 : 0) }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700 w-12 text-right">{{ $row['percentage'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 text-center text-xs whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold">{{ $row['resolved'] }} Selesai</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-sky-50 text-sky-700 font-semibold">{{ $row['monitoring'] }} Pantau</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold">{{ $row['open'] }} Terbuka</span>
                                </td>
                                <td class="py-3.5 pl-3 pr-6 text-xs text-slate-600 max-w-xs">
                                    @if (!empty($row['sample_titles']))
                                        <ul class="list-disc list-inside space-y-0.5">
                                            @foreach ($row['sample_titles'] as $title)
                                                <li class="truncate" title="{{ $title }}">{{ $title }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada kasus bulan ini</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sebaran Tingkat Urgensi & Kelas -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Distribusi Urgensi -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <h3 class="text-sm font-bold text-slate-900 mb-1">2. Tingkat Urgensi Masalah</h3>
                <p class="text-xs text-slate-500 mb-4">Klasifikasi keparahan kasus pada bulan {{ $monthLabel }}</p>
                <div class="space-y-3">
                    @foreach ($severityBreakdown as $sev)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                            <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $sev['enum']->badgeClasses() }}">
                                Urgensi {{ $sev['label'] }}
                            </span>
                            <div class="text-right">
                                <span class="text-sm font-extrabold text-slate-900">{{ $sev['count'] }} Kasus</span>
                                <span class="text-xs text-slate-500 ml-1">({{ $sev['percentage'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Distribusi per Kelas -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <h3 class="text-sm font-bold text-slate-900 mb-1">3. Sebaran Kasus per Kelas</h3>
                <p class="text-xs text-slate-500 mb-4">Kelas dengan catatan layanan BK pada {{ $monthLabel }}</p>
                @if (empty($classBreakdown))
                    <p class="text-xs text-slate-400 italic">Belum ada data kelas pada bulan ini.</p>
                @else
                    <div class="space-y-2.5">
                        @foreach ($classBreakdown as $cls)
                            <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-100 last:border-0">
                                <span class="font-bold text-slate-800">{{ $cls['class'] }}</span>
                                <span class="font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full">
                                    {{ $cls['count'] }} kasus ({{ $cls['percentage'] }}%)
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section 3: Tabel Rincian Lengkap Kasus, Penanganan, Tindak Lanjut & Rangkuman Konseling Bulanan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    4. Daftar Rincian Kasus, Penanganan, Tindak Lanjut & Rangkuman Konseling ({{ $monthLabel }})
                </h2>
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $records->count() }} catatan kasus
                    @if ($selectedCategory)
                        pada kategori <strong>{{ $selectedCategory }}</strong>
                    @endif
                </p>
            </div>
        </div>

        @if ($records->isEmpty())
            <div class="p-10 text-center text-sm text-slate-500">
                Tidak ada catatan kasus atau konseling pada bulan <strong>{{ $monthLabel }}</strong>.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3 pl-6 pr-2">No</th>
                            <th class="px-2 py-3">Identitas Siswa</th>
                            <th class="px-2 py-3">Kategori & Masalah/Case</th>
                            <th class="px-2 py-3">Tgl Kejadian & Konseling</th>
                            <th class="px-2 py-3">Penanganan & Tindak Lanjut</th>
                            <th class="px-2 py-3">Rangkuman Konseling</th>
                            <th class="py-3 pl-2 pr-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white align-top">
                        @foreach ($records as $index => $record)
                            <tr class="hover:bg-slate-50/70">
                                <td class="py-3.5 pl-6 pr-2 font-bold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-2 py-3.5 whitespace-nowrap">
                                    <a href="{{ route('bk.show', $record) }}" class="font-bold text-slate-900 hover:text-indigo-600 block text-sm">
                                        {{ $record->student_name }}
                                    </a>
                                    <span class="text-slate-600 font-medium block">{{ $record->student_class }} &bull; NIS: {{ $record->student_id_number }}</span>
                                    <span class="font-mono text-[11px] text-slate-400">{{ $record->case_code }}</span>
                                </td>
                                <td class="px-2 py-3.5 max-w-xs">
                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $record->case_category->badgeClasses() }} mb-1">
                                        {{ $record->case_category->value }}
                                    </span>
                                    <p class="font-bold text-slate-900">{{ $record->case_title }}</p>
                                    <p class="text-slate-600 mt-0.5">{{ $record->problem_description }}</p>
                                </td>
                                <td class="px-2 py-3.5 whitespace-nowrap">
                                    <div class="mb-1.5">
                                        <span class="text-[10px] uppercase font-bold text-rose-600 block">Kejadian:</span>
                                        <span class="font-semibold text-slate-800">{{ $record->incident_date->locale('id')->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-indigo-600 block">Konseling:</span>
                                        <span class="font-semibold text-slate-800">{{ $record->counseling_date->locale('id')->translatedFormat('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-3.5 max-w-xs space-y-1.5">
                                    <div>
                                        <span class="font-bold text-slate-800 block">Penanganan:</span>
                                        <p class="text-slate-600">{{ $record->handling_action }}</p>
                                    </div>
                                    @if ($record->follow_up)
                                        <div>
                                            <span class="font-bold text-slate-800 block">Tindak Lanjut:</span>
                                            <p class="text-slate-600">{{ $record->follow_up }}</p>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-2 py-3.5 max-w-xs">
                                    <p class="text-slate-800">{{ $record->counseling_summary }}</p>
                                    <span class="block mt-1 text-[11px] font-semibold text-indigo-700">Konselor: {{ $record->counselor_name }}</span>
                                </td>
                                <td class="py-3.5 pl-2 pr-6 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $record->status->badgeClasses() }}">
                                        {{ $record->status->value }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Signature Block for Monthly Report Printout -->
        <div class="px-8 py-6 border-t border-slate-200 bg-slate-50/40 grid grid-cols-2 gap-8 text-xs text-slate-600">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold text-slate-800">Kepala Sekolah</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline">........................................................</p>
                <p>NIP. ..................................................</p>
            </div>
            <div class="text-right">
                <p>Dilaporkan pada: {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="font-bold text-slate-800">Koordinator Bimbingan & Konseling (BK)</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline">Dra. Hj. Siti Nurhaliza, M.Pd.</p>
                <p>NIP. 19780412 200501 2 004</p>
            </div>
        </div>
    </div>
</div>
@endsection

