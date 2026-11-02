<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login SI-BIKO — Sistem Informasi Bimbingan & Konseling</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="min-h-full font-sans text-slate-800 antialiased flex items-center justify-center p-4 sm:p-6 lg:p-10 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/10">
        <!-- Left Column: Brand & Role Access Matrix -->
        <div class="lg:col-span-6 bg-gradient-to-br from-indigo-900 via-indigo-800 to-violet-900 p-8 sm:p-10 text-white flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-xs flex items-center justify-center text-white shadow-inner">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl tracking-tight">SI-BIKO</span>
                        <p class="text-xs text-indigo-200">Sistem Informasi Bimbingan & Konseling</p>
                    </div>
                </div>

                <h1 class="mt-8 text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight">
                    Portal Akses Terpadu Layanan Konseling Sekolah
                </h1>
                <p class="mt-2.5 text-sm text-indigo-100/90 leading-relaxed">
                    Silakan masuk menggunakan akun sesuai peran dan kewenangan Anda. Klik salah satu kartu akun demo di bawah untuk mengisi kredensial otomatis:
                </p>

                <!-- Quick Demo Login Cards -->
                <div class="mt-6 space-y-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-200">Pilih Akun Demo (Klik untuk isi otomatis):</p>

                    <button type="button"
                            onclick="fillCredentials('superadmin@bk.sch.id', 'password')"
                            class="w-full text-left rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 p-3.5 transition-all cursor-pointer group">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-white group-hover:text-indigo-200">1. Super Admin</span>
                            <span class="text-[11px] font-mono bg-indigo-500/40 px-2 py-0.5 rounded text-indigo-100">superadmin@bk.sch.id</span>
                        </div>
                        <p class="text-xs text-indigo-100/80 mt-1">Akses penuh: Identitas, Kasus & Konseling, Rekap, Laporan Bulanan & Manajemen Akun.</p>
                    </button>

                    <button type="button"
                            onclick="fillCredentials('psikologi@bk.sch.id', 'password')"
                            class="w-full text-left rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 p-3.5 transition-all cursor-pointer group">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-white group-hover:text-emerald-200">2. Psikologi / Konselor</span>
                            <span class="text-[11px] font-mono bg-emerald-500/40 px-2 py-0.5 rounded text-emerald-100">psikologi@bk.sch.id</span>
                        </div>
                        <p class="text-xs text-indigo-100/80 mt-1">Semua akses (Identitas, Pencatatan Kasus/Konseling, Rekap & Laporan) kecuali Manajemen Akun.</p>
                    </button>

                    <button type="button"
                            onclick="fillCredentials('staff@bk.sch.id', 'password')"
                            class="w-full text-left rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 p-3.5 transition-all cursor-pointer group">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-white group-hover:text-amber-200">3. Staff Administrasi</span>
                            <span class="text-[11px] font-mono bg-amber-500/40 px-2 py-0.5 rounded text-amber-100">staff@bk.sch.id</span>
                        </div>
                        <p class="text-xs text-indigo-100/80 mt-1">Hanya akses menu Pencatatan Identitas Siswa dan Rekap Kasus.</p>
                    </button>

                    <button type="button"
                            onclick="fillCredentials('atasan@bk.sch.id', 'password')"
                            class="w-full text-left rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 p-3.5 transition-all cursor-pointer group">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-white group-hover:text-purple-200">4. Atasan / Kepala Sekolah</span>
                            <span class="text-[11px] font-mono bg-purple-500/40 px-2 py-0.5 rounded text-purple-100">atasan@bk.sch.id</span>
                        </div>
                        <p class="text-xs text-indigo-100/80 mt-1">Hanya akses menu Rekap Kasus dan Laporan Bulanan BK.</p>
                    </button>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-white/15 text-xs text-indigo-200 flex items-center justify-between">
                <span>Kata sandi seluruh akun demo: <strong class="font-mono text-white">password</strong></span>
                <span>SI-BIKO v2.0</span>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md mx-auto w-full">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Masuk ke Dashboard BK</h2>
                <p class="text-sm text-slate-500 mt-1">Masukkan email dan kata sandi akun Anda untuk melanjutkan</p>

                @if (session('success'))
                    <div class="mt-5 rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 text-xs font-medium text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs font-medium text-rose-800">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                        <input type="email"
                               name="email"
                               id="email"
                               required
                               autofocus
                               value="{{ old('email', 'superadmin@bk.sch.id') }}"
                               placeholder="nama@bk.sch.id"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600/20">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               value="password"
                               placeholder="••••••••"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600/20">
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="inline-flex items-center gap-2 text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>Ingat sesi login saya</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-500 transition-colors cursor-pointer">
                        <span>Masuk ke Aplikasi</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </form>

                <!-- Matrix Hak Akses Ringkas -->
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Tabel Matriks Hak Akses Role</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-[11px] text-left border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-50 text-slate-600 font-bold">
                                <tr>
                                    <th class="p-2">Role</th>
                                    <th class="p-2 text-center">Identitas</th>
                                    <th class="p-2 text-center">Rekap</th>
                                    <th class="p-2 text-center">Konseling</th>
                                    <th class="p-2 text-center">Laporan</th>
                                    <th class="p-2 text-center">Akun</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-700">
                                <tr>
                                    <td class="p-2 font-bold">Super Admin</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold">Psikologi</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-rose-400">&times;</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold">Staff</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-rose-400">&times;</td>
                                    <td class="p-2 text-center text-rose-400">&times;</td>
                                    <td class="p-2 text-center text-rose-400">&times;</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-bold">Atasan</td>
                                    <td class="p-2 text-center text-rose-400">&times;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-rose-400">&times;</td>
                                    <td class="p-2 text-center text-emerald-600 font-bold">&check;</td>
                                    <td class="p-2 text-center text-rose-400">&times;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>

