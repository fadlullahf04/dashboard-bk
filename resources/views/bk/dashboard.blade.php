@extends('layouts.app')

@section('page_title', 'Dashboard Utama Bimbingan & Konseling')
@section('page_subtitle', 'Ringkasan eksekutif layanan konseling, pantauan kasus prioritas, dan statistik bulan ' . $activeMonthLabel)

@section('content')
<div class="space-y-6">
    <!-- Welcome & Quick Navigation Cards -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-violet-900 rounded-2xl p-6 sm:p-8 text-white shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-indigo-100 mb-3">
                    <span>Periode Aktif: {{ $activeMonthLabel }}</span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Selamat Datang di Dashboard BK (SI-BIKO)
                </h1>
                <p class="mt-2 text-sm text-indigo-100/90 leading-relaxed">
                    Pantau perkembangan kasus siswa, kelola tindak lanjut konseling, telusuri riwayat permasalahan berdasarkan kategori atau tanggal, dan cetak laporan bulanan BK dari satu panel terpusat.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if (auth()->user()?->canManageCounselingCases())
                    <a href="{{ route('bk.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-indigo-900 shadow-xs hover:bg-indigo-50 transition-colors">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Catat Kasus Baru</span>
                    </a>
                @elseif (auth()->user()?->canAccessIdentitas())
                    <a href="{{ route('bk.identitas') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-indigo-900 shadow-xs hover:bg-indigo-50 transition-colors">
                        <span>Kelola Identitas Siswa</span>
                    </a>
                @endif
                <a href="{{ route('bk.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-indigo-700/70 border border-indigo-400/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                    <span>Rekap Data Kasus</span>
                </a>
                @if (auth()->user()?->canAccessLaporan())
                    <a href="{{ route('bk.report', ['month' => $activeMonth]) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-700/70 border border-indigo-400/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                        <span>Laporan Bulanan</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 4 Key Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Kasus Bulan Ini</span>
                <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-extrabold text-slate-900">{{ $stats['monthly_total'] }} <span class="text-sm font-semibold text-slate-500">Case</span></div>
                <p class="mt-1 text-xs text-slate-500">
                    Total keseluruhan database: <strong class="text-slate-700">{{ $stats['total_all'] }} kasus</strong>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Ragam Masalah ({{ $activeMonthLabel }})</span>
                <span class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-extrabold text-slate-900">{{ $stats['distinct_categories_this_month'] }} <span class="text-sm font-semibold text-slate-500">/ {{ $stats['total_categories'] }} Kategori</span></div>
                <p class="mt-1 text-xs text-slate-500">Variasi kategori masalah pada bulan ini</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Perlu Tindak Lanjut</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-extrabold text-amber-600">{{ $stats['monthly_open'] + $stats['monthly_monitoring'] }} <span class="text-sm font-semibold text-slate-500">Kasus</span></div>
                <p class="mt-1 text-xs text-slate-500">
                    <span class="font-semibold text-amber-700">{{ $stats['monthly_open'] }} Terbuka</span> &bull;
                    <span class="font-semibold text-sky-700">{{ $stats['monthly_monitoring'] }} Dipantau</span>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Kasus Selesai ({{ $activeMonthLabel }})</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-extrabold text-emerald-600">{{ $stats['monthly_resolved'] }} <span class="text-sm font-semibold text-slate-500">Kasus</span></div>
                <p class="mt-1 text-xs text-slate-500">Penanganan & konseling telah tuntas</p>
            </div>
        </div>
    </div>

    <!-- Middle Grid: Peta Ragam Masalah & Kasus Prioritas yang Sedang Dipantau -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Peta Ragam Masalah Bulan Ini -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Ragam Masalah Bulan {{ $activeMonthLabel }}</h3>
                        <p class="text-xs text-slate-500">Klik salah satu kategori untuk membuka Data Kasus terfilter</p>
                    </div>
                    <a href="{{ route('bk.report', ['month' => $activeMonth]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                        Buka Laporan &rarr;
                    </a>
                </div>

                <div class="space-y-3.5 mt-4">
                    @foreach ($categorySummary as $item)
                        <a href="{{ route('bk.index', ['category' => $item['label'], 'month' => $activeMonth]) }}"
                           class="block group rounded-xl p-2 -mx-2 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">
                                    {{ $item['label'] }}
                                </span>
                                <span class="font-bold text-slate-900">
                                    {{ $item['count'] }} kasus ({{ $item['percentage'] }}%)
                                </span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $item['enum']->barColorClass() }}"
                                     style="width: {{ max($item['percentage'], $item['count'] > 0 ? 6 : 0) }}%"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kasus Prioritas / Dalam Pemantauan Aktif -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Kasus Aktif Perlu Tindak Lanjut</h3>
                    <p class="text-xs text-slate-500">Daftar kasus berstatus Terbuka atau Dalam Pemantauan berdasarkan urgensi</p>
                </div>
                <a href="{{ route('bk.index', ['status' => 'Terbuka']) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Semua Kasus &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100 mt-2">
                @forelse ($priorityRecords as $record)
                    <div class="py-3.5 flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $record->severity->badgeClasses() }}">
                                    Urgensi {{ $record->severity->value }}
                                </span>
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $record->status->badgeClasses() }}">
                                    {{ $record->status->value }}
                                </span>
                                <span class="text-xs text-slate-400">&bull; {{ $record->incident_date->locale('id')->translatedFormat('d M Y') }}</span>
                            </div>
                            <a href="{{ route('bk.show', $record) }}" class="font-bold text-sm text-slate-900 hover:text-indigo-600 block truncate">
                                {{ $record->student_name }} ({{ $record->student_class }})
                            </a>
                            <p class="text-xs text-slate-600 truncate mt-0.5">{{ $record->case_title }}</p>
                            @if ($record->follow_up)
                                <p class="text-[11px] text-indigo-700 font-medium mt-1 truncate">
                                    Tindak Lanjut: {{ $record->follow_up }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('bk.show', $record) }}"
                           class="shrink-0 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            Detail
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-500">
                        Seluruh kasus telah selesai ditangani.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Catatan Konseling Terbaru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-900">Aktivitas Pencatatan Kasus & Konseling Terbaru</h3>
                <p class="text-xs text-slate-500">5 rekam jejak konseling terakhir yang dicatat ke dalam sistem</p>
            </div>
            <a href="{{ route('bk.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                Buka Menu Data Kasus & Pencarian &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 pl-6 pr-3">Siswa & Kelas</th>
                        <th class="px-3 py-3">Kategori & Masalah</th>
                        <th class="px-3 py-3">Tgl Kejadian & Konseling</th>
                        <th class="px-3 py-3">Rangkuman Konseling</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="py-3 pl-3 pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white align-top">
                    @foreach ($recentRecords as $record)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 pl-6 pr-3 whitespace-nowrap">
                                <a href="{{ route('bk.show', $record) }}" class="font-bold text-slate-900 hover:text-indigo-600 block">
                                    {{ $record->student_name }}
                                </a>
                                <span class="text-xs text-slate-500">{{ $record->student_class }} &bull; NIS: {{ $record->student_id_number }}</span>
                            </td>
                            <td class="px-3 py-3.5 max-w-xs">
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $record->case_category->badgeClasses() }} mb-1">
                                    {{ $record->case_category->value }}
                                </span>
                                <p class="text-xs font-semibold text-slate-800 line-clamp-1">{{ $record->case_title }}</p>
                            </td>
                            <td class="px-3 py-3.5 whitespace-nowrap text-xs text-slate-600">
                                <div><strong class="text-rose-600">Kejadian:</strong> {{ $record->incident_date->locale('id')->translatedFormat('d M Y') }}</div>
                                <div class="mt-0.5"><strong class="text-indigo-600">Konseling:</strong> {{ $record->counseling_date->locale('id')->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="px-3 py-3.5 max-w-sm text-xs text-slate-600">
                                <p class="line-clamp-2">{{ $record->counseling_summary }}</p>
                            </td>
                            <td class="px-3 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $record->status->badgeClasses() }}">
                                    {{ $record->status->value }}
                                </span>
                            </td>
                            <td class="py-3.5 pl-3 pr-6 text-right whitespace-nowrap">
                                <a href="{{ route('bk.show', $record) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                                    Buka Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

