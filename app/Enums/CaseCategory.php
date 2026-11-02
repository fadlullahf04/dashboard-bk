<?php

namespace App\Enums;

enum CaseCategory: string
{
    case Kedisiplinan = 'Kedisiplinan & Tata Tertib';
    case Akademik = 'Akademik & Belajar';
    case SosialPerundungan = 'Sosial & Perundungan (Bullying)';
    case Kehadiran = 'Kehadiran & Keterlambatan';
    case KeluargaPribadi = 'Keluarga & Masalah Pribadi';
    case Karier = 'Karier & Minat Bakat';
    case Lainnya = 'Lainnya';

    /**
     * Return badge color classes for Tailwind UI.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Kedisiplinan => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            self::Akademik => 'bg-blue-50 text-blue-700 ring-blue-600/20',
            self::SosialPerundungan => 'bg-purple-50 text-purple-700 ring-purple-600/20',
            self::Kehadiran => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::KeluargaPribadi => 'bg-teal-50 text-teal-700 ring-teal-600/20',
            self::Karier => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
            self::Lainnya => 'bg-slate-100 text-slate-700 ring-slate-600/20',
        };
    }

    /**
     * Return solid bar color class for charts.
     */
    public function barColorClass(): string
    {
        return match ($this) {
            self::Kedisiplinan => 'bg-rose-500',
            self::Akademik => 'bg-blue-500',
            self::SosialPerundungan => 'bg-purple-500',
            self::Kehadiran => 'bg-amber-500',
            self::KeluargaPribadi => 'bg-teal-500',
            self::Karier => 'bg-indigo-500',
            self::Lainnya => 'bg-slate-500',
        };
    }
}

