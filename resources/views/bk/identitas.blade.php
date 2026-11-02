@extends('layouts.app')

@section('page_title', 'Data & Pencatatan Identitas Siswa')
@section('page_subtitle', 'Kelola direktori identitas siswa, wali kelas, kontak orang tua, serta registrasi awal siswa')

@section('content')
<div class="space-y-6">
    <!-- Top Section: Form Registrasi Identitas Siswa Baru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-900">Formulir Pencatatan Identitas Siswa Baru</h2>
                <p class="text-xs text-slate-500">Dapat diakses oleh Super Admin, Psikologi, dan Staff Administrasi untuk mencatat identitas siswa</p>
            </div>
            <span class="rounded-lg bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                Akses: {{ auth()->user()->role->label() }}
            </span>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('bk.identitas.store') }}" method="POST" class="mt-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" name="student_name" required value="{{ old('student_name') }}" placeholder="Contoh: Raka Pratama"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIS / NISN <span class="text-rose-500">*</span></label>
                    <input type="text" name="student_id_number" required value="{{ old('student_id_number') }}" placeholder="Contoh: 25101188"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" name="student_class" required value="{{ old('student_class') }}" placeholder="Contoh: X-MIPA-1"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="gender" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Wali Kelas</label>
                    <input type="text" name="homeroom_teacher" value="{{ old('homeroom_teacher') }}" placeholder="Contoh: Hendra Wijaya, S.Pd."
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kontak Orang Tua / Wali</label>
                    <input type="text" name="parent_contact" value="{{ old('parent_contact') }}" placeholder="Contoh: Bpk. Budi (0812-xxxx)"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Case Awal <span class="text-rose-500">*</span></label>
                    <select name="case_category" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                        @foreach ($categories as $category)
                            <option value="{{ $category->value }}">{{ $category->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Registrasi / Kejadian <span class="text-rose-500">*</span></label>
                    <input type="date" name="incident_date" required value="{{ old('incident_date', now()->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan Singkat / Keperluan Layanan BK <span class="text-rose-500">*</span></label>
                    <input type="text" name="case_title" required value="{{ old('case_title') }}" placeholder="Contoh: Pendaftaran identitas awal siswa untuk jadwal konseling"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-indigo-500 transition-colors cursor-pointer">
                        + Simpan Identitas Siswa
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Search & Table Direktori Identitas Siswa -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-900">Direktori Identitas Siswa Terdaftar</h3>
                <p class="text-xs text-slate-500">Perbarui nama, NIS/NISN, kelas, wali kelas, atau kontak orang tua siswa</p>
            </div>

            <form action="{{ route('bk.identitas') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NIS / wali kelas..."
                       class="rounded-xl border border-slate-300 bg-white px-3.5 py-1.5 text-xs focus:border-indigo-500 focus:outline-none">
                <select name="class" class="rounded-xl border border-slate-300 bg-white px-3 py-1.5 text-xs focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach ($availableClasses as $cls)
                        <option value="{{ $cls }}" @selected($classFilter === $cls)>{{ $cls }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-xl bg-slate-900 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 cursor-pointer">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 pl-6 pr-3">Nama Siswa</th>
                        <th class="px-3 py-3">NIS / NISN</th>
                        <th class="px-3 py-3">Kelas & L/P</th>
                        <th class="px-3 py-3">Wali Kelas</th>
                        <th class="px-3 py-3">Kontak Orang Tua / Wali</th>
                        <th class="py-3 pl-3 pr-6 text-right">Perbarui Identitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white align-middle">
                    @foreach ($records as $record)
                        <tr class="hover:bg-slate-50/70">
                            <form action="{{ route('bk.identitas.update', $record) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td class="py-3 pl-6 pr-3">
                                    <input type="text" name="student_name" value="{{ $record->student_name }}" required
                                           class="w-full min-w-[160px] rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-bold text-slate-900 focus:border-indigo-500 focus:outline-none">
                                </td>
                                <td class="px-3 py-3">
                                    <input type="text" name="student_id_number" value="{{ $record->student_id_number }}" required
                                           class="w-28 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-mono text-slate-800 focus:border-indigo-500 focus:outline-none">
                                </td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" name="student_class" value="{{ $record->student_class }}" required
                                               class="w-24 rounded-lg border border-slate-200 px-2 py-1.5 text-xs font-semibold text-slate-800 focus:border-indigo-500 focus:outline-none">
                                        <select name="gender" class="rounded-lg border border-slate-200 px-2 py-1.5 text-xs text-slate-800">
                                            <option value="L" @selected($record->gender === 'L')>L</option>
                                            <option value="P" @selected($record->gender === 'P')>P</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <input type="text" name="homeroom_teacher" value="{{ $record->homeroom_teacher }}"
                                           class="w-40 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none">
                                </td>
                                <td class="px-3 py-3">
                                    <input type="text" name="parent_contact" value="{{ $record->parent_contact }}"
                                           class="w-44 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none">
                                </td>
                                <td class="py-3 pl-3 pr-6 text-right whitespace-nowrap">
                                    <button type="submit"
                                            class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-600 hover:text-white transition-colors cursor-pointer">
                                        Simpan
                                    </button>
                                    <a href="{{ route('bk.show', $record) }}"
                                       class="ml-1 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                        Rekap
                                    </a>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($records->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

