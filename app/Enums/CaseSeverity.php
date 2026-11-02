<?php

namespace App\Enums;

enum CaseSeverity: string
{
    case Low = 'Ringan';
    case Medium = 'Sedang';
    case High = 'Berat';

    /**
     * Return badge color classes for Tailwind UI.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Low => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            self::Medium => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::High => 'bg-red-50 text-red-700 ring-red-600/20',
        };
    }
}

