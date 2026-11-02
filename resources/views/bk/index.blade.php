@extends('layouts.app')

@section('page_title', 'Data Kasus & Pencarian Siswa')
@section('page_subtitle', 'Telusuri data kasus dan konseling berdasarkan nama siswa, kategori case, tanggal spesifik, atau bulan')

@section('content')
<div class="space-y-6">
    <!-- Filter Result Summary Strip -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Data Ditemukan</span>
                <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $filterSummary['total_found'] }} <span class="text-xs font-semibold text-slate-500">Kasus</span></p>
            </div>
            <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Status Terbuka</span>
                <p class="text-2xl font-extrabold text-amber-600 mt-0.5">{{ $filterSummary['open_count'] }} <span class="text-xs font-semibold text-slate-500">Kasus</span></p>
            </div>
            <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-sky-600">Dalam Pemantauan</span>
                <p class="text-2xl font-extrabold text-sky-600 mt-0.5">{{ $filterSummary['monitoring_count'] }} <span class="text-xs font-semibold text-slate-500">Kasus</span></p>
            </div>
            <span class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Selesai Ditangani</span>
                <p class="text-2xl font-extrabold text-emerald-600 mt-0.5">{{ $filterSummary['resolved_count'] }} <span class="text-xs font-semibold text-slate-500">Kasus</span></p>
            </div>
            <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Search & Filter Box -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Filter & Pencarian Data Kasus BK</h2>
                    <p class="text-xs text-slate-500">Cari berdasarkan Nama Siswa, Kategori Case, Tanggal Spesifik, atau Bulan</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if (array_filter($filters, fn ($v, $k) => $k !== 'date_field' && !empty($v), ARRAY_FILTER_USE_BOTH))
                    <a href="{{ route('bk.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        <span>Reset Semua Filter</span>
                    </a>
                @endif
                @if (auth()->user()?->canManageCounselingCases())
                    <a href="{{ route('bk.create') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors">
                        <span>+ Catat Kasus Baru</span>
                    </a>
                @elseif (auth()->user()?->canAccessIdentitas())
                    <a href="{{ route('bk.identitas') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors">
                        <span>+ Catat Identitas Siswa</span>
                    </a>
                @endif
            </div>
        </div>

        <form action="{{ route('bk.index') }}" method="GET" class="mt-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4">
                <!-- Cari Nama Siswa / NIS / Masalah -->
                <div class="lg:col-span-4">
                    <label for="search" class="block text-xs font-semibold text-slate-700 mb-1.5">Cari Nama Siswa / NIS / Kata Kunci</label>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ $filters['search'] }}"
                           placeholder="Ketik nama siswa, NIS, kelas, atau kasus..."
                           class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <!-- Filter Kategori Case -->
                <div class="lg:col-span-3">
                    <label for="category" class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori Case / Masalah</label>
                    <select name="category"
                            id="category"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">-- Semua Kategori Case --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->value }}" @selected($filters['category'] === $category->value)>
                                {{ $category->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Bulan (YYYY-MM) -->
                <div class="lg:col-span-2">
                    <label for="month" class="block text-xs font-semibold text-slate-700 mb-1.5">Filter Bulan</label>
                    <input type="month"
                           name="month"
                           id="month"
                           value="{{ $filters['month'] }}"
                           class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <!-- Filter Tanggal Spesifik -->
                <div class="lg:col-span-3">
                    <label for="date" class="block text-xs font-semibold text-slate-700 mb-1.5">Filter Tanggal Spesifik</label>
                    <input type="date"
                           name="date"
                           id="date"
                           value="{{ $filters['date'] }}"
                           class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 pt-1 items-end">
                <div class="lg:col-span-3">
                    <label for="date_field" class="block text-xs font-semibold text-slate-700 mb-1.5">Basis Filter Tanggal/Bulan</label>
                    <select name="date_field"
                            id="date_field"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="incident_date" @selected($filters['date_field'] !== 'counseling_date')>Berdasarkan Tanggal Kejadian</option>
                        <option value="counseling_date" @selected($filters['date_field'] === 'counseling_date')>Berdasarkan Tanggal Konseling</option>
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <label for="class" class="block text-xs font-semibold text-slate-700 mb-1.5">Kelas</label>
                    <select name="class"
                            id="class"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">-- Semua Kelas --</option>
                        @foreach ($availableClasses as $className)
                            <option value="{{ $className }}" @selected($filters['class'] === $className)>{{ $className }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Penanganan</label>
                    <select name="status"
                            id="status"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">-- Semua Status --</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>{{ $status->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-3 flex items-center gap-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <span>Terapkan Pencarian</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Records List Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-900">Tabel Direktori Kasus & Konseling Siswa</h3>
                <p class="text-xs text-slate-500">
                    Menampilkan <strong class="text-slate-800">{{ $records->total() }}</strong> catatan kasus yang sesuai dengan filter pencarian
                </p>
            </div>
            @if (!empty($filters['month']))
                <a href="{{ route('bk.report', ['month' => $filters['month']]) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    <span>Buka Laporan Bulan {{ $filters['month'] }}</span>
                    <span>&rarr;</span>
                </a>
            @endif
        </div>

        @if ($records->isEmpty())
            <div class="p-12 text-center">
                <h4 class="text-base font-bold text-slate-900">Tidak ada data kasus ditemukan</h4>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Belum ada catatan kasus atau konseling yang cocok dengan filter pencarian Anda.
                </p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    <a href="{{ route('bk.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Reset Filter
                    </a>
                    <a href="{{ route('bk.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500">
                        + Catat Kasus Baru
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3">Identitas Siswa</th>
                            <th scope="col" class="px-3 py-3.5">Masalah / Case</th>
                            <th scope="col" class="px-3 py-3.5">Tanggal Kejadian & Konseling</th>
                            <th scope="col" class="px-3 py-3.5">Penanganan, Tindak Lanjut & Rangkuman</th>
                            <th scope="col" class="px-3 py-3.5">Status</th>
                            <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 bg-white align-top">
                        @foreach ($records as $record)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                            {{ strtoupper(substr($record->student_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('bk.show', $record) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition-colors block">
                                                {{ $record->student_name }}
                                            </a>
                                            <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                                <span class="font-medium text-slate-700">{{ $record->student_class }}</span>
                                                <span>&bull;</span>
                                                <span>NIS: {{ $record->student_id_number }}</span>
                                            </div>
                                            <span class="inline-block mt-1 font-mono text-[11px] text-slate-400">{{ $record->case_code }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-4 max-w-xs">
                                    <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $record->case_category->badgeClasses() }}">
                                            {{ $record->case_category->value }}
                                        </span>
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $record->severity->badgeClasses() }}">
                                            Urgensi: {{ $record->severity->value }}
                                        </span>
                                    </div>
                                    <p class="font-semibold text-slate-900 line-clamp-2">{{ $record->case_title }}</p>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $record->problem_description }}</p>
                                </td>

                                <td class="px-3 py-4 whitespace-nowrap text-xs">
                                    <div class="space-y-2">
                                        <div class="bg-rose-50/60 border border-rose-100 rounded-lg px-2.5 py-1.5">
                                            <span class="block text-[10px] font-semibold uppercase tracking-wider text-rose-600">Tgl Kejadian</span>
                                            <span class="font-bold text-slate-800">{{ $record->incident_date->locale('id')->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <div class="bg-indigo-50/60 border border-indigo-100 rounded-lg px-2.5 py-1.5">
                                            <span class="block text-[10px] font-semibold uppercase tracking-wider text-indigo-600">Tgl Konseling</span>
                                            <span class="font-bold text-slate-800">{{ $record->counseling_date->locale('id')->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-4 max-w-md text-xs space-y-1.5">
                                    <div>
                                        <span class="font-bold text-slate-700">Penanganan:</span>
                                        <span class="text-slate-600 line-clamp-1">{{ $record->handling_action }}</span>
                                    </div>
                                    @if ($record->follow_up)
                                        <div>
                                            <span class="font-bold text-slate-700">Tindak Lanjut:</span>
                                            <span class="text-slate-600 line-clamp-1">{{ $record->follow_up }}</span>
                                        </div>
                                    @endif
                                    <div class="bg-slate-50 rounded-lg p-2 border border-slate-100">
                                        <span class="font-bold text-indigo-700 block">Rangkuman Konseling ({{ $record->counselor_name }}):</span>
                                        <p class="text-slate-600 line-clamp-2 mt-0.5">{{ $record->counseling_summary }}</p>
                                    </div>
                                </td>

                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $record->status->badgeClasses() }}">
                                        {{ $record->status->value }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('bk.show', $record) }}"
                                           class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                            Detail
                                        </a>
                                        @if (auth()->user()?->canManageCounselingCases())
                                            <a href="{{ route('bk.edit', $record) }}"
                                               class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($records->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/80 bg-slate-50/40">
                    {{ $records->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
