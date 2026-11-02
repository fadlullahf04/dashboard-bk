<?php

namespace Database\Seeders;

use App\Enums\CaseCategory;
use App\Enums\CaseSeverity;
use App\Enums\CaseStatus;
use App\Enums\UserRole;
use App\Models\CounselingRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultUsers = [
            [
                'name' => 'Administrator Utama BK',
                'email' => 'superadmin@bk.sch.id',
                'role' => UserRole::SuperAdmin->value,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Dra. Hj. Siti Nurhaliza, M.Pd. (Psikologi)',
                'email' => 'psikologi@bk.sch.id',
                'role' => UserRole::Psikologi->value,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Andi Saputra, A.Md. (Staff TU/BK)',
                'email' => 'staff@bk.sch.id',
                'role' => UserRole::Staff->value,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Drs. H. Mulyono, M.M. (Kepala Sekolah)',
                'email' => 'atasan@bk.sch.id',
                'role' => UserRole::Atasan->value,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($defaultUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        if (CounselingRecord::count() > 0) {
            return;
        }

        $now = Carbon::now();
        $currentMonth = $now->format('Y-m');
        $prevMonth = (clone $now)->subMonth()->format('Y-m');

        $records = [
            [
                'case_code' => 'BK-'.$now->format('Ym').'-001',
                'student_name' => 'Aditya Pratama Putra',
                'student_id_number' => '24101101',
                'student_class' => 'XI-MIPA-1',
                'gender' => 'L',
                'homeroom_teacher' => 'Hendra Wijaya, S.Pd.',
                'parent_contact' => 'Bpk. Pratama (0812-3456-7890)',
                'case_category' => CaseCategory::Kehadiran->value,
                'case_title' => 'Terlambat hadir sekolah 4 kali berturut-turut pada jam pertama',
                'problem_description' => 'Siswa datang terlambat lebih dari 20 menit selama 4 hari berturut-turut pada pekan pertama bulan ini. Berdasarkan laporan guru piket, siswa tampak mengantuk saat tiba di sekolah.',
                'severity' => CaseSeverity::Medium->value,
                'incident_date' => $currentMonth.'-01',
                'handling_action' => 'Pemanggilan siswa ke ruang BK untuk konseling individual dan identifikasi pola tidur serta aktivitas malam di rumah.',
                'follow_up' => 'Koordinasi dengan orang tua terkait pembatasan penggunaan gawai di atas pukul 21.30 WIB serta pemantauan presensi selama 2 minggu ke depan.',
                'status' => CaseStatus::Monitoring->value,
                'counseling_date' => $currentMonth.'-02',
                'counseling_summary' => 'Siswa mengakui sering bermain game online hingga pukul 01.00 dini hari sehingga sulit bangun pagi. Siswa berkomitmen membuat jadwal tidur teratur dan menyerahkan HP kepada orang tua malam hari.',
                'counselor_name' => 'Dra. Hj. Siti Nurhaliza, M.Pd.',
            ],
            [
                'case_code' => 'BK-'.$now->format('Ym').'-002',
                'student_name' => 'Nabila Azzahra Putri',
                'student_id_number' => '23102045',
                'student_class' => 'XII-MIPA-2',
                'gender' => 'P',
                'homeroom_teacher' => 'Ratna Sari Dewi, M.Pd.',
                'parent_contact' => 'Ibu Dewi (0813-9876-5432)',
                'case_category' => CaseCategory::Karier->value,
                'case_title' => 'Kebimbangan pemilihan jurusan SNBP/SNBT dan kecemasan akademik kelas XII',
                'problem_description' => 'Siswa mengalami kecemasan tinggi dan penurunan fokus belajar karena perbedaan keinginan jurusan kuliah antara minat pribadi (Desain Komunikasi Visual) dan harapan orang tua (Kedokteran/Farmasi).',
                'severity' => CaseSeverity::Low->value,
                'incident_date' => $currentMonth.'-01',
                'handling_action' => 'Pelaksanaan asesmen minat bakat lanjutan, pemberian informasi prospek karier kedua bidang, dan sesi konseling karier.',
                'follow_up' => 'Mengundang orang tua untuk konsultasi karier bersama pekan depan guna menyelaraskan pilihan studi lanjut.',
                'status' => CaseStatus::Monitoring->value,
                'counseling_date' => $currentMonth.'-02',
                'counseling_summary' => 'Siswa merasa lebih tenang setelah memetakan portofolio dan alternatif program studi seperti Desain Produk/Teknologi Biomedis yang menjembatani minat dan harapan keluarga.',
                'counselor_name' => 'Budi Santoso, S.Pd., Kons.',
            ],
            [
                'case_code' => 'BK-'.$now->format('Ym').'-003',
                'student_name' => 'Rizky Ramadhan',
                'student_id_number' => '25101018',
                'student_class' => 'X-IPS-1',
                'gender' => 'L',
                'homeroom_teacher' => 'Ahmad Fauzi, S.Pd.',
                'parent_contact' => 'Bpk. Rahmat (0857-1122-3344)',
                'case_category' => CaseCategory::SosialPerundungan->value,
                'case_title' => 'Perselisihan verbal dengan teman sekelas di grup WhatsApp angkatan',
                'problem_description' => 'Terjadi saling ejek di grup percakapan kelas yang memicu ketegangan antar kelompok pertemanan di kelas X-IPS-1 saat jam istirahat.',
                'severity' => CaseSeverity::High->value,
                'incident_date' => $currentMonth.'-01',
                'handling_action' => 'Mediasi langsung antara pihak-pihak yang terlibat bersama Wali Kelas dan Guru BK, serta edukasi etika komunikasi digital.',
                'follow_up' => 'Evaluasi iklim pertemanan kelas melalui sosiometri singkat dan pendampingan ketua kelas.',
                'status' => CaseStatus::Resolved->value,
                'counseling_date' => $currentMonth.'-01',
                'counseling_summary' => 'Kedua siswa menyadari kesalahpahaman berawal dari candaan yang melewati batas. Keduanya telah saling memaafkan, menghapus pesan negatif, dan menandatangani kesepakatan damai.',
                'counselor_name' => 'Rina Kartika, S.Psi.',
            ],
            [
                'case_code' => 'BK-'.$now->format('Ym').'-004',
                'student_name' => 'Dimas Anggara',
                'student_id_number' => '24102088',
                'student_class' => 'XI-IPS-2',
                'gender' => 'L',
                'homeroom_teacher' => 'Lestari Handayani, S.Pd.',
                'parent_contact' => 'Ibu Sri Wahyuni (0811-2233-4455)',
                'case_category' => CaseCategory::Kedisiplinan->value,
                'case_title' => 'Tidak mengenakan atribut seragam lengkap dan keluar area sekolah saat jam KBM',
                'problem_description' => 'Siswa kedapatan berada di kantin luar sekolah pada jam pelajaran ke-5 dan tidak mengenakan atribut dasi serta sabuk sesuai tata tertib sekolah.',
                'severity' => CaseSeverity::High->value,
                'incident_date' => $currentMonth.'-02',
                'handling_action' => 'Penertiban bersama tim Tatib, pencatatan poin pelanggaran, dan sesi konseling direktif mengenai tanggung jawab pelajar.',
                'follow_up' => 'Pemanggilan orang tua/wali apabila pelanggaran berulang serta penugasan refleksi diri.',
                'status' => CaseStatus::Open->value,
                'counseling_date' => $currentMonth.'-02',
                'counseling_summary' => 'Siswa merasa jenuh dengan mata pelajaran siang hari dan terpengaruh ajakan teman. Siswa berjanji tidak mengulangi perbuatan membolos jam pelajaran.',
                'counselor_name' => 'Dra. Hj. Siti Nurhaliza, M.Pd.',
            ],
            [
                'case_code' => 'BK-'.$now->format('Ym').'-005',
                'student_name' => 'Clara Nathania',
                'student_id_number' => '24101055',
                'student_class' => 'XI-MIPA-2',
                'gender' => 'P',
                'homeroom_teacher' => 'Dedi Kurniawan, S.Si.',
                'parent_contact' => 'Bpk. Antonius (0818-7788-9900)',
                'case_category' => CaseCategory::Akademik->value,
                'case_title' => 'Penurunan nilai drastis pada mata pelajaran Fisika dan Kimia semester berjalan',
                'problem_description' => 'Wali kelas melaporkan nilai evaluasi harian siswa turun signifikan dibanding semester lalu dan siswa sering terlihat murung di kelas.',
                'severity' => CaseSeverity::Medium->value,
                'incident_date' => $currentMonth.'-02',
                'handling_action' => 'Konseling individual diagnostik kesulitan belajar dan koordinasi dengan guru mata pelajaran terkait klinik akademik.',
                'follow_up' => 'Mengikutsertakan siswa dalam kelompok belajar sebaya (peer tutoring) setiap hari Kamis sepulang sekolah.',
                'status' => CaseStatus::Monitoring->value,
                'counseling_date' => $currentMonth.'-02',
                'counseling_summary' => 'Siswa kesulitan mengikuti metode pembelajaran cepat dan malu bertanya di kelas. Setelah dipasangkan dengan tutor sebaya, motivasi belajar siswa kembali meningkat.',
                'counselor_name' => 'Budi Santoso, S.Pd., Kons.',
            ],
            [
                'case_code' => 'BK-'.$now->format('Ym').'-006',
                'student_name' => 'Fajar Siddiq',
                'student_id_number' => '25101032',
                'student_class' => 'X-MIPA-1',
                'gender' => 'L',
                'homeroom_teacher' => 'Siti Aminah, S.Pd.',
                'parent_contact' => 'Ibu Hasanah (0821-5544-3322)',
                'case_category' => CaseCategory::KeluargaPribadi->value,
                'case_title' => 'Kurang konsentrasi belajar akibat permasalahan ekonomi dan kondisi kesehatan orang tua',
                'problem_description' => 'Siswa beberapa kali tidak mengumpulkan tugas tepat waktu karena harus membantu menjaga orang tua yang sedang dirawat jalan.',
                'severity' => CaseSeverity::Medium->value,
                'incident_date' => $currentMonth.'-01',
                'handling_action' => 'Konseling empatik, penguatan resiliensi psikologis siswa, serta pengajuan rekomendasi keringanan/beasiswa internal sekolah.',
                'follow_up' => 'Home visit bersama wali kelas dan penyaluran bantuan dana sosial komite sekolah.',
                'status' => CaseStatus::Resolved->value,
                'counseling_date' => $currentMonth.'-02',
                'counseling_summary' => 'Siswa merasa sangat terbantu secara moral setelah menceritakan bebannya. Guru mapel telah diberikan informasi agar memberikan dispensasi waktu pengumpulan tugas.',
                'counselor_name' => 'Rina Kartika, S.Psi.',
            ],
            [
                'case_code' => 'BK-'.str_replace('-', '', $prevMonth).'-001',
                'student_name' => 'Bagas Prakoso',
                'student_id_number' => '23103012',
                'student_class' => 'XII-IPS-1',
                'gender' => 'L',
                'homeroom_teacher' => 'Drs. Bambang Sudibyo',
                'parent_contact' => 'Bpk. Eko (0815-6677-8899)',
                'case_category' => CaseCategory::Kedisiplinan->value,
                'case_title' => 'Pelanggaran tata tertib parkir kendaraan dan knalpot bising di lingkungan sekolah',
                'problem_description' => 'Siswa membawa sepeda motor dengan knalpot tidak standar yang mengganggu ketenangan kegiatan belajar mengajar.',
                'severity' => CaseSeverity::Medium->value,
                'incident_date' => $prevMonth.'-14',
                'handling_action' => 'Teguran lisan dan tertulis, penahanan kunci kendaraan sementara hingga jam pulang, serta konseling kedisiplinan.',
                'follow_up' => 'Siswa wajib mengganti knalpot standar sebelum diizinkan membawa kendaraan kembali ke sekolah.',
                'status' => CaseStatus::Resolved->value,
                'counseling_date' => $prevMonth.'-15',
                'counseling_summary' => 'Siswa memahami aturan ketertiban lingkungan sekolah dan keesokan harinya telah mengganti knalpot sesuai standar pabrikan.',
                'counselor_name' => 'Dra. Hj. Siti Nurhaliza, M.Pd.',
            ],
            [
                'case_code' => 'BK-'.str_replace('-', '', $prevMonth).'-002',
                'student_name' => 'Putri Maharani',
                'student_id_number' => '24101099',
                'student_class' => 'XI-MIPA-1',
                'gender' => 'P',
                'homeroom_teacher' => 'Hendra Wijaya, S.Pd.',
                'parent_contact' => 'Ibu Maharani (0819-3344-5566)',
                'case_category' => CaseCategory::Akademik->value,
                'case_title' => 'Manajemen waktu tidak seimbang antara kegiatan OSIS/Ekstrakurikuler dan akademik',
                'problem_description' => 'Siswa aktif sebagai pengurus inti OSIS namun sering tertinggal materi pelajaran karena padatnya agenda persiapan acara sekolah.',
                'severity' => CaseSeverity::Low->value,
                'incident_date' => $prevMonth.'-20',
                'handling_action' => 'Bimbingan penyusunan matriks skala prioritas (Eisenhower Matrix) dan jadwal belajar mingguan.',
                'follow_up' => 'Evaluasi nilai tengah semester dan pembatasan izin dispensasi jam pelajaran.',
                'status' => CaseStatus::Resolved->value,
                'counseling_date' => $prevMonth.'-21',
                'counseling_summary' => 'Siswa berhasil menyusun jadwal delegasi tugas organisasi sehingga tidak lagi meninggalkan kelas pada mata pelajaran inti.',
                'counselor_name' => 'Budi Santoso, S.Pd., Kons.',
            ],
        ];

        foreach ($records as $record) {
            CounselingRecord::create($record);
        }
    }
}
