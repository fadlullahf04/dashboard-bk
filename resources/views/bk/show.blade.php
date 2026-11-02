@extends('layouts.app')

@section('page_title', 'Detail Kasus & Lembar Konseling Siswa')
@section('page_subtitle', 'Nomor Registrasi: ' . $record->case_code . ' — ' . $record->student_name . ' (' . $record->student_class . ')')

@section('content')
<div class="w-full space-y-6">
    <!-- Top Bar Navigation & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print">
        <div>
            <a href="{{ route('bk.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700 mb-1">
                <span>&larr; Kembali ke Direktori Kasus</span>
            </a>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lembar Rekam Kasus & Konseling BK</h1>
                <span class="font-mono text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-200/80 text-slate-700">{{ $record->case_code }}</span>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
                <span>Cetak Lembar Konseling</span>
            </button>

            @if (auth()->user()?->canManageCounselingCases())
                <a href="{{ route('bk.edit', $record) }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors">
                    <span>Edit Catatan</span>
                </a>

                <form action="{{ route('bk.destroy', $record) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kasus ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition-colors cursor-pointer">
                        <span>Hapus</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Document Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Document Header Banner -->
        <div class="p-6 sm:p-8 border-b border-slate-200 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-white/15 text-white">
                            {{ $record->case_category->value }}
                        </span>
                        <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-white/10 text-indigo-200">
                            Urgensi: {{ $record->severity->value }}
                        </span>
                        <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-emerald-400/20 text-emerald-200">
                            Status: {{ $record->status->value }}
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">{{ $record->case_title }}</h2>
                </div>
                <div class="text-left md:text-right shrink-0">
                    <p class="text-xs text-indigo-200 uppercase tracking-wider font-semibold">Nomor Registrasi BK</p>
                    <p class="text-lg font-mono font-bold text-white">{{ $record->case_code }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <!-- 1. Identitas Siswa & Tanggal Penting -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Identitas Siswa -->
                <div class="lg:col-span-7 bg-slate-50 rounded-2xl p-5 border border-slate-200/70">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-3">1. Identitas Siswa</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-slate-500">Nama Lengkap Siswa</dt>
                            <dd class="font-bold text-slate-900 mt-0.5">{{ $record->student_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">NIS / NISN</dt>
                            <dd class="font-semibold text-slate-800 mt-0.5">{{ $record->student_id_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Kelas & Jenis Kelamin</dt>
                            <dd class="font-semibold text-slate-800 mt-0.5">
                                {{ $record->student_class }} ({{ $record->gender === 'P' ? 'Perempuan' : 'Laki-laki' }})
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Wali Kelas</dt>
                            <dd class="font-semibold text-slate-800 mt-0.5">{{ $record->homeroom_teacher ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-slate-500">Kontak Orang Tua / Wali</dt>
                            <dd class="font-semibold text-slate-800 mt-0.5">{{ $record->parent_contact ?: '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Tanggal Kejadian, Tanggal Konseling & Konselor -->
                <div class="lg:col-span-5 bg-indigo-50/50 rounded-2xl p-5 border border-indigo-100 flex flex-col justify-between space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-700">Linimasa Kejadian & Konseling</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white rounded-xl p-3.5 border border-rose-200/80">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600 block">Tanggal Kejadian</span>
                            <span class="text-base font-extrabold text-slate-900 mt-1 block">
                                {{ $record->incident_date->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="bg-white rounded-xl p-3.5 border border-indigo-200/80">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 block">Tanggal Konseling</span>
                            <span class="text-base font-extrabold text-slate-900 mt-1 block">
                                {{ $record->counseling_date->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 text-xs">
                        <span class="text-slate-500 block">Guru BK / Konselor Pendamping:</span>
                        <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $record->counselor_name }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Detail Masalah / Case -->
            <div class="space-y-4">
                <div class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-rose-600">2. Deskripsi Masalah / Kronologi Case</h3>
                        <span class="text-xs font-semibold text-slate-500">Kategori: {{ $record->case_category->value }}</span>
                    </div>
                    <p class="text-sm text-slate-800 leading-relaxed whitespace-pre-line">{{ $record->problem_description }}</p>
                </div>

                <!-- 3. Penanganan & Tindak Lanjut -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-200 p-5 bg-amber-50/30">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-700 mb-2">3. Langkah Penanganan</h3>
                        <p class="text-sm text-slate-800 leading-relaxed whitespace-pre-line">{{ $record->handling_action }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5 bg-sky-50/30">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-sky-700 mb-2">4. Rencana & Catatan Tindak Lanjut</h3>
                        <p class="text-sm text-slate-800 leading-relaxed whitespace-pre-line">{{ $record->follow_up ?: 'Belum ada catatan tindak lanjut tambahan.' }}</p>
                    </div>
                </div>

                <!-- 5. Rangkuman Konseling -->
                <div class="rounded-2xl border-2 border-indigo-100 bg-indigo-50/30 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-700">5. Rangkuman Sesi Konseling</h3>
                        <span class="text-xs font-medium text-slate-600">
                            Dilaksanakan pada {{ $record->counseling_date->locale('id')->translatedFormat('d F Y') }} oleh <strong>{{ $record->counselor_name }}</strong>
                        </span>
                    </div>
                    <p class="text-sm text-slate-900 leading-relaxed whitespace-pre-line">{{ $record->counseling_summary }}</p>
                </div>
            </div>

            <!-- Signature Block for Print -->
            <div class="pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-slate-600">
                <div>
                    <p>Mengetahui,</p>
                    <p class="font-bold text-slate-800">Wali Kelas {{ $record->student_class }}</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-slate-900 underline">{{ $record->homeroom_teacher ?: '............................................' }}</p>
                </div>
                <div class="text-right">
                    <p>{{ $record->counseling_date->locale('id')->translatedFormat('d F Y') }}</p>
                    <p class="font-bold text-slate-800">Guru Bimbingan & Konseling</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-slate-900 underline">{{ $record->counselor_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Kasus Lain dari Siswa Ini -->
    @if ($studentHistory->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 no-print">
            <h3 class="text-base font-bold text-slate-900 mb-3">
                Riwayat Kasus & Konseling Lainnya dari {{ $record->student_name }} ({{ $studentHistory->count() }} catatan)
            </h3>
            <div class="divide-y divide-slate-200 text-sm">
                @foreach ($studentHistory as $history)
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs text-slate-500">{{ $history->case_code }}</span>
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $history->case_category->badgeClasses() }}">
                                    {{ $history->case_category->value }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $history->incident_date->locale('id')->translatedFormat('d M Y') }}</span>
                            </div>
                            <p class="font-semibold text-slate-900 mt-1">{{ $history->case_title }}</p>
                        </div>
                        <a href="{{ route('bk.show', $history) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 shrink-0">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

