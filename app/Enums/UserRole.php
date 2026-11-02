<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Psikologi = 'psikologi';
    case Staff = 'staff';
    case Atasan = 'atasan';

    /**
     * Human-readable role title.
     */
    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Psikologi => 'Psikologi / Konselor',
            self::Staff => 'Staff Administrasi',
            self::Atasan => 'Atasan / Pimpinan',
        };
    }

    /**
     * Role access summary description.
     */
    public function description(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Akses penuh ke seluruh fitur, Identitas, Kasus/Konseling, Rekap, Laporan & Manajemen Akun',
            self::Psikologi => 'Akses penuh Identitas, Kasus/Konseling, Rekap & Laporan Bulanan (kecuali Manajemen Akun)',
            self::Staff => 'Hanya akses pencatatan/kelola Identitas Siswa dan melihat Rekap Kasus',
            self::Atasan => 'Hanya akses Rekap Kasus dan Laporan Bulanan BK',
        };
    }

    /**
     * Tailwind badge classes for the role.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::SuperAdmin => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
            self::Psikologi => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            self::Staff => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::Atasan => 'bg-purple-50 text-purple-700 ring-purple-600/20',
        };
    }
}

