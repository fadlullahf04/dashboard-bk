<?php

namespace App\Enums;

enum CaseStatus: string
{
    case Open = 'Terbuka';
    case Monitoring = 'Dalam Pemantauan';
    case Resolved = 'Selesai';

    /**
     * Return badge color classes for Tailwind UI.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Open => 'bg-amber-50 text-amber-800 ring-amber-600/20',
            self::Monitoring => 'bg-sky-50 text-sky-800 ring-sky-600/20',
            self::Resolved => 'bg-emerald-50 text-emerald-800 ring-emerald-600/20',
        };
    }
}

