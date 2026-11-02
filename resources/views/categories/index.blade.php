@extends('layouts.app')

@section('page_title', 'Kelola Kategori Case / Masalah BK')
@section('page_subtitle', 'Tambah, ubah nama, sesuaikan warna label, atau hapus kategori permasalahan Bimbingan & Konseling')

@section('content')
<div class="w-full space-y-6">
    @if (session('error'))
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm font-medium text-rose-800 flex items-center gap-3">
            <span class="w-6 h-6 rounded-full bg-rose-600 text-white text-xs font-bold flex items-center justify-center shrink-0">!</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Top Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Kategori Case</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $categories->count() }} <span class="text-sm font-semibold text-slate-500">Kategori</span></p>
            </div>
            <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Kategori Terpakai</span>
                <p class="text-3xl font-extrabold text-emerald-600 mt-1">{{ count(array_filter($caseCounts, fn ($c) => $c > 0)) }} <span class="text-sm font-semibold text-slate-500">Aktif</span></p>
            </div>
            <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-violet-600">Total Kasus Tercatat</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ array_sum($caseCounts) }} <span class="text-sm font-semibold text-slate-500">Kasus</span></p>
            </div>
            <span class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Form Tambah Kategori Case Baru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-900">Tambah Kategori Case Baru</h2>
                <p class="text-xs text-slate-500">Kategori yang ditambahkan akan langsung muncul di form pencatatan, filter pencarian, sidebar, dan laporan bulanan BK</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" class="mt-5 grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            @csrf
            <div class="md:col-span-4">
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Kategori Case <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       required
                       value="{{ old('name') }}"
                       placeholder="Contoh: Kesehatan Mental & Emosi"
                       class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="md:col-span-3">
                <label for="color" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Warna Label & Grafik <span class="text-rose-500">*</span>
                </label>
                <select name="color"
                        id="color"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    @foreach ($colorThemes as $colorKey => $theme)
                        <option value="{{ $colorKey }}" @selected(old('color') === $colorKey)>{{ $theme['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Deskripsi / Cakupan Masalah
                </label>
                <input type="text"
                       name="description"
                       id="description"
                       value="{{ old('description') }}"
                       placeholder="Keterangan singkat cakupan kategori..."
                       class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="md:col-span-2">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-indigo-500 transition-colors cursor-pointer">
                    <span>+ Tambah Kategori</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar & Edit Kategori Case -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-slate-900">Daftar Kategori Case Aktif</h3>
                <p class="text-xs text-slate-500">Ubah nama kategori, warna badge, atau deskripsi secara langsung pada baris tabel di bawah</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 pl-6 pr-3">Pratinjau Badge</th>
                        <th class="px-3 py-3.5">Nama Kategori Case</th>
                        <th class="px-3 py-3.5">Warna Label</th>
                        <th class="px-3 py-3.5">Deskripsi Singkat</th>
                        <th class="px-3 py-3.5 text-center">Jumlah Kasus</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white align-middle">
                    @foreach ($categories as $category)
                        @php
                            $count = (int) ($caseCounts[$category->name] ?? 0);
                        @endphp
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3.5 pl-6 pr-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $category->badgeClasses() }}">
                                    <span class="w-2 h-2 rounded-full {{ $category->barColorClass() }}"></span>
                                    <span>{{ $category->name }}</span>
                                </span>
                            </td>

                            <td class="px-3 py-3.5">
                                <input type="text"
                                       name="name"
                                       form="update-category-{{ $category->id }}"
                                       value="{{ $category->name }}"
                                       required
                                       class="w-full min-w-[200px] rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-900 focus:border-indigo-500 focus:outline-none">
                            </td>

                            <td class="px-3 py-3.5">
                                <select name="color"
                                        form="update-category-{{ $category->id }}"
                                        class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-800 focus:border-indigo-500 focus:outline-none">
                                    @foreach ($colorThemes as $colorKey => $theme)
                                        <option value="{{ $colorKey }}" @selected($category->color === $colorKey)>
                                            {{ $theme['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="px-3 py-3.5">
                                <input type="text"
                                       name="description"
                                       form="update-category-{{ $category->id }}"
                                       value="{{ $category->description }}"
                                       placeholder="Deskripsi kategori..."
                                       class="w-full min-w-[220px] rounded-lg border border-slate-200 px-3 py-1.5 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none">
                            </td>

                            <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                <a href="{{ route('bk.index', ['category' => $category->name]) }}"
                                   class="inline-flex items-center gap-1 rounded-full bg-slate-100 hover:bg-indigo-50 px-3 py-1 text-xs font-bold text-slate-700 hover:text-indigo-700 transition-colors">
                                    <span>{{ $count }} Kasus</span>
                                </a>
                            </td>

                            <td class="py-3.5 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <form id="update-category-{{ $category->id }}"
                                          action="{{ route('categories.update', $category) }}"
                                          method="POST"
                                          class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-600 hover:text-white transition-colors cursor-pointer">
                                            Simpan
                                        </button>
                                    </form>

                                    <form action="{{ route('categories.destroy', $category) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Hapus kategori {{ $category->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition-colors cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

