@extends('layouts.app')

@section('page_title', 'Edit Catatan Kasus & Konseling (' . $record->case_code . ')')
@section('page_subtitle', 'Perbarui data penanganan, tindak lanjut, atau rangkuman konseling untuk ' . $record->student_name)

@section('content')
<div class="w-full space-y-6">
    <!-- Top Info Banner Full Width -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-extrabold text-slate-900">Perbarui Data Kasus & Konseling</h1>
                    <span class="font-mono text-xs font-bold bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-lg">{{ $record->case_code }}</span>
                </div>
                <p class="text-xs text-slate-500">Siswa: <strong>{{ $record->student_name }}</strong> ({{ $record->student_class }} &bull; NIS: {{ $record->student_id_number }})</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('bk.show', $record) }}"
               class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <span>&larr; Kembali ke Detail Kasus</span>
            </a>
        </div>
    </div>

    <form action="{{ route('bk.update', $record) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')
        @include('bk._form')
    </form>
</div>
@endsection
