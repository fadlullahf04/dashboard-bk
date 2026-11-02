@php
    $isEdit = isset($record);
@endphp

@if ($errors->any())
    <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">!</div>
            <div>
                <h3 class="text-sm font-bold text-rose-900">Mohon periksa kembali isian formulir Anda:</h3>
                <ul class="mt-1.5 list-disc list-inside text-xs text-rose-800 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="space-y-6 w-full">
    <!-- Proportional Full-Width 2x2 Grid Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-stretch">
        <!-- Card 1: Identitas Siswa (Left Top - 6 cols) -->
        <div class="xl:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold text-sm flex items-center justify-center shrink-0">1</span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Identitas Siswa</h2>
                            <p class="text-xs text-slate-500">Data identitas lengkap siswa yang mendapatkan layanan BK</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Bagian 1 / 4</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
                    <div class="sm:col-span-2">
                        <label for="student_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Lengkap Siswa <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="student_name"
                               id="student_name"
                               required
                               value="{{ old('student_name', $record->student_name ?? '') }}"
                               placeholder="Contoh: Aditya Pratama Putra"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div>
                        <label for="student_id_number" class="block text-xs font-bold text-slate-700 mb-1.5">
                            NIS / NISN <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="student_id_number"
                               id="student_id_number"
                               required
                               value="{{ old('student_id_number', $record->student_id_number ?? '') }}"
                               placeholder="Contoh: 24101101"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div>
                        <label for="student_class" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kelas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="student_class"
                               id="student_class"
                               list="class-suggestions"
                               required
                               value="{{ old('student_class', $record->student_class ?? '') }}"
                               placeholder="Contoh: XI-MIPA-1"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <datalist id="class-suggestions">
                            <option value="X-MIPA-1"></option>
                            <option value="X-MIPA-2"></option>
                            <option value="X-IPS-1"></option>
                            <option value="X-IPS-2"></option>
                            <option value="XI-MIPA-1"></option>
                            <option value="XI-MIPA-2"></option>
                            <option value="XI-IPS-1"></option>
                            <option value="XI-IPS-2"></option>
                            <option value="XII-MIPA-1"></option>
                            <option value="XII-MIPA-2"></option>
                            <option value="XII-IPS-1"></option>
                            <option value="XII-IPS-2"></option>
                        </datalist>
                    </div>

                    <div>
                        <label for="gender" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select name="gender"
                                id="gender"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            <option value="L" @selected(old('gender', $record->gender ?? 'L') === 'L')>Laki-laki (L)</option>
                            <option value="P" @selected(old('gender', $record->gender ?? 'L') === 'P')>Perempuan (P)</option>
                        </select>
                    </div>

                    <div>
                        <label for="homeroom_teacher" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Wali Kelas
                        </label>
                        <input type="text"
                               name="homeroom_teacher"
                               id="homeroom_teacher"
                               value="{{ old('homeroom_teacher', $record->homeroom_teacher ?? '') }}"
                               placeholder="Contoh: Hendra Wijaya, S.Pd."
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="parent_contact" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama & Kontak Orang Tua / Wali
                        </label>
                        <input type="text"
                               name="parent_contact"
                               id="parent_contact"
                               value="{{ old('parent_contact', $record->parent_contact ?? '') }}"
                               placeholder="Contoh: Bpk. Pratama (0812-3456-7890)"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Masalah / Case & Tanggal Kejadian (Right Top - 6 cols) -->
        <div class="xl:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-rose-600 text-white font-bold text-sm flex items-center justify-center shrink-0">2</span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Masalah / Case & Tanggal Kejadian</h2>
                            <p class="text-xs text-slate-500">Kategori permasalahan, urgensi, tanggal kejadian & kronologi</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg">Bagian 2 / 4</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
                    <div class="sm:col-span-2">
                        <label for="case_category" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kategori Case / Masalah <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $selectedCat = old('case_category', isset($record) ? $record->case_category->value : '');
                        @endphp
                        <select name="case_category"
                                id="case_category"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            <option value="" disabled @selected(empty($selectedCat))>-- Pilih Kategori Masalah --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->value }}" @selected($selectedCat === $category->value)>
                                    {{ $category->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="incident_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Kejadian <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               name="incident_date"
                               id="incident_date"
                               required
                               value="{{ old('incident_date', isset($record) ? $record->incident_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div>
                        <label for="severity" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tingkat Urgensi / Keparahan <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $selectedSeverity = old('severity', isset($record) ? $record->severity->value : 'Sedang');
                        @endphp
                        <select name="severity"
                                id="severity"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            @foreach ($severities as $severity)
                                <option value="{{ $severity->value }}" @selected($selectedSeverity === $severity->value)>
                                    {{ $severity->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="case_title" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul Singkat Masalah / Case <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="case_title"
                               id="case_title"
                               required
                               value="{{ old('case_title', $record->case_title ?? '') }}"
                               placeholder="Contoh: Terlambat hadir sekolah 4 kali berturut-turut pada jam pertama"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="problem_description" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Deskripsi Masalah / Kronologi Kejadian <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="problem_description"
                                  id="problem_description"
                                  rows="3"
                                  required
                                  placeholder="Uraikan kronologi kejadian, gejala, atau laporan permasalahan yang dialami siswa..."
                                  class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('problem_description', $record->problem_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Penanganan & Tindak Lanjut (Left Bottom - 6 cols) -->
        <div class="xl:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-amber-500 text-white font-bold text-sm flex items-center justify-center shrink-0">3</span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Penanganan & Tindak Lanjut</h2>
                            <p class="text-xs text-slate-500">Langkah penanganan yang ditempuh serta rencana tindak lanjut</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg">Bagian 3 / 4</span>
                </div>

                <div class="grid grid-cols-1 gap-4 mt-5">
                    <div>
                        <label for="handling_action" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Langkah Penanganan yang Dilakukan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="handling_action"
                                  id="handling_action"
                                  rows="3"
                                  required
                                  placeholder="Tuliskan tindakan penanganan yang dilakukan oleh Guru BK / Psikologi / Wali Kelas..."
                                  class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('handling_action', $record->handling_action ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="follow_up" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Rencana / Catatan Tindak Lanjut
                        </label>
                        <textarea name="follow_up"
                                  id="follow_up"
                                  rows="3"
                                  placeholder="Contoh: Pemantauan presensi 2 minggu ke depan, pemanggilan orang tua, atau home visit..."
                                  class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('follow_up', $record->follow_up ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Status Penanganan Kasus <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $selectedStatus = old('status', isset($record) ? $record->status->value : 'Dalam Pemantauan');
                        @endphp
                        <select name="status"
                                id="status"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                                    {{ $status->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Pelaksanaan & Rangkuman Konseling (Right Bottom - 6 cols) -->
        <div class="xl:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-bold text-sm flex items-center justify-center shrink-0">4</span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Pelaksanaan & Rangkuman Konseling</h2>
                            <p class="text-xs text-slate-500">Tanggal konseling, konselor pendamping & rangkuman hasil sesi</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">Bagian 4 / 4</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
                    <div>
                        <label for="counseling_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Konseling <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               name="counseling_date"
                               id="counseling_date"
                               required
                               value="{{ old('counseling_date', isset($record) ? $record->counseling_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div>
                        <label for="counselor_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Guru BK / Psikologi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="counselor_name"
                               id="counselor_name"
                               list="counselor-suggestions"
                               required
                               value="{{ old('counselor_name', $record->counselor_name ?? auth()->user()?->name ?? 'Dra. Hj. Siti Nurhaliza, M.Pd.') }}"
                               placeholder="Nama Guru BK yang menangani"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <datalist id="counselor-suggestions">
                            <option value="Dra. Hj. Siti Nurhaliza, M.Pd."></option>
                            <option value="Budi Santoso, S.Pd., Kons."></option>
                            <option value="Rina Kartika, S.Psi."></option>
                        </datalist>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="counseling_summary" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Rangkuman Hasil Konseling <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="counseling_summary"
                                  id="counseling_summary"
                                  rows="6"
                                  required
                                  placeholder="Tuliskan rangkuman jalannya sesi konseling, dinamika psikologis siswa, komitmen perubahan perilaku, serta kesimpulan konselor..."
                                  class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('counseling_summary', $record->counseling_summary ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action Bar Full Width -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-xs text-slate-500">
            Pastikan seluruh kolom bertanda bintang (<span class="text-rose-500 font-bold">*</span>) telah terisi dengan benar sebelum menyimpan data.
        </p>
        <div class="flex items-center justify-end gap-3 shrink-0">
            <a href="{{ $isEdit ? route('bk.show', $record) : route('bk.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                <span>{{ $isEdit ? 'Simpan Perubahan Data BK' : 'Simpan Catatan Kasus & Konseling' }}</span>
            </button>
        </div>
    </div>
</div>
