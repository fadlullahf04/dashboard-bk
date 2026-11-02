@extends('layouts.app')

@section('page_title', 'Pencatatan Kasus & Konseling Baru')
@section('page_subtitle', 'Formulir input identitas siswa, masalah/case, tanggal kejadian, penanganan, tindak lanjut, dan rangkuman konseling')

@section('content')
<div class="w-full space-y-6">
    <!-- Top Info Banner Full Width -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-slate-900">Lembar Pencatatan Kasus & Konseling Siswa</h1>
                <p class="text-xs text-slate-500">Isi keempat bagian di bawah secara lengkap. Nomor registrasi kasus akan dibuat otomatis oleh sistem.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('bk.index') }}"
               class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <span>&larr; Lihat Rekap Kasus</span>
            </a>
        </div>
    </div>

    <form action="{{ route('bk.store') }}" method="POST" class="w-full">
        @csrf
        @include('bk._form')
    </form>
</div>
@endsection
