@extends('layouts.app')

@section('page_title', 'Manajemen Akun & Hak Akses Pengguna')
@section('page_subtitle', 'Khusus Super Admin: Kelola akun login dan pembagian hak akses Super Admin, Psikologi, Staff, dan Atasan')

@section('content')
<div class="space-y-6">
    @if (session('error'))
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm font-medium text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Role Access Explanation Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($roles as $role)
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-bold ring-1 ring-inset {{ $role->badgeClasses() }}">
                    {{ $role->label() }}
                </span>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">{{ $role->description() }}</p>
            </div>
        @endforeach
    </div>

    <!-- Form Tambah Akun Baru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <h2 class="text-base font-bold text-slate-900">Tambah Akun Pengguna Baru</h2>
        <p class="text-xs text-slate-500 mt-0.5">Buat akun baru dan tentukan hak aksesnya sesuai struktur organisasi sekolah</p>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Nama pengguna & gelar"
                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Email Login <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="user@bk.sch.id"
                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Role / Hak Akses <span class="text-rose-500">*</span></label>
                <select name="role" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" @selected(old('role') === $role->value)>{{ $role->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <button type="submit"
                        class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-indigo-500 transition-colors cursor-pointer">
                    + Buat Akun
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Akun Terdaftar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-900">Daftar Akun Pengguna Sistem ({{ $users->count() }} Akun)</h3>
            <p class="text-xs text-slate-500">Anda dapat langsung mengubah nama, email, role hak akses, atau mereset kata sandi pengguna</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 pl-6 pr-3">Nama Pengguna</th>
                        <th class="px-3 py-3">Alamat Email</th>
                        <th class="px-3 py-3">Role / Hak Akses</th>
                        <th class="px-3 py-3">Password Baru (Opsional)</th>
                        <th class="py-3 pl-3 pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white align-middle">
                    @foreach ($users as $userItem)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3 pl-6 pr-3">
                                <input type="text" name="name" form="update-user-{{ $userItem->id }}" value="{{ $userItem->name }}" required
                                       class="w-full min-w-[180px] rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-bold text-slate-900">
                            </td>
                            <td class="px-3 py-3">
                                <input type="email" name="email" form="update-user-{{ $userItem->id }}" value="{{ $userItem->email }}" required
                                       class="w-48 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-mono text-slate-800">
                            </td>
                            <td class="px-3 py-3">
                                <select name="role" form="update-user-{{ $userItem->id }}"
                                        class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-800">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->value }}" @selected($userItem->role === $role)>{{ $role->label() }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-3">
                                <input type="password" name="password" form="update-user-{{ $userItem->id }}" placeholder="Kosongkan jika tetap"
                                       class="w-40 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs text-slate-700">
                            </td>
                            <td class="py-3 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <form id="update-user-{{ $userItem->id }}" action="{{ route('users.update', $userItem) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-600 hover:text-white transition-colors cursor-pointer">
                                            Simpan
                                        </button>
                                    </form>

                                    @if (auth()->id() !== $userItem->id)
                                        <form action="{{ route('users.destroy', $userItem) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus akun {{ $userItem->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
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

