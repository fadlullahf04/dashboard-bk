<?php

namespace App\Models;

use App\Enums\CaseCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class CaseCategoryItem extends Model
{
    protected $table = 'case_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'color',
        'description',
    ];

    /**
     * Provide `$category->value` alias for `$category->name` so Blade templates work seamlessly.
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->name,
        );
    }

    /**
     * Available color themes for case categories.
     *
     * @return array<string, array{label: string, badge: string, bar: string}>
     */
    public static function colorThemes(): array
    {
        return [
            'rose' => [
                'label' => 'Merah Rose (Kedisiplinan / Darurat)',
                'badge' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                'bar' => 'bg-rose-500',
            ],
            'blue' => [
                'label' => 'Biru (Akademik / Belajar)',
                'badge' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                'bar' => 'bg-blue-500',
            ],
            'purple' => [
                'label' => 'Ungu (Sosial / Perundungan)',
                'badge' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                'bar' => 'bg-purple-500',
            ],
            'amber' => [
                'label' => 'Kuning Amber (Kehadiran / Keterlambatan)',
                'badge' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                'bar' => 'bg-amber-500',
            ],
            'teal' => [
                'label' => 'Hijau Toska (Keluarga & Pribadi)',
                'badge' => 'bg-teal-50 text-teal-700 ring-teal-600/20',
                'bar' => 'bg-teal-500',
            ],
            'indigo' => [
                'label' => 'Indigo (Karier & Minat Bakat)',
                'badge' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                'bar' => 'bg-indigo-500',
            ],
            'emerald' => [
                'label' => 'Hijau Emerald (Pengembangan Diri)',
                'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                'bar' => 'bg-emerald-500',
            ],
            'slate' => [
                'label' => 'Abu-abu Slate (Umum / Lainnya)',
                'badge' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                'bar' => 'bg-slate-500',
            ],
        ];
    }

    /**
     * Return badge color classes for Tailwind UI.
     */
    public function badgeClasses(): string
    {
        $themes = self::colorThemes();

        return $themes[$this->color]['badge'] ?? $themes['indigo']['badge'];
    }

    /**
     * Return solid bar color class for charts.
     */
    public function barColorClass(): string
    {
        $themes = self::colorThemes();

        return $themes[$this->color]['bar'] ?? $themes['indigo']['bar'];
    }

    /**
     * Seed default categories if table is empty, and return all categories.
     *
     * @return Collection<int, CaseCategoryItem>
     */
    public static function allResolved(): Collection
    {
        if (! Schema::hasTable('case_categories')) {
            return collect(self::defaultDefinitions())->map(fn (array $item): self => new self($item));
        }

        if (self::count() === 0) {
            foreach (self::defaultDefinitions() as $def) {
                self::create($def);
            }
        }

        return self::orderBy('id')->get();
    }

    /**
     * Resolve a CaseCategoryItem instance by category name.
     */
    public static function resolveByName(?string $name): self
    {
        $categoryName = $name ?: CaseCategory::Lainnya->value;

        if (Schema::hasTable('case_categories')) {
            $found = self::where('name', $categoryName)->first();
            if ($found) {
                return $found;
            }
        }

        foreach (self::defaultDefinitions() as $def) {
            if ($def['name'] === $categoryName) {
                return new self($def);
            }
        }

        return new self([
            'name' => $categoryName,
            'color' => 'indigo',
            'description' => 'Kategori kasus bimbingan & konseling',
        ]);
    }

    /**
     * Default 7 BK categories.
     *
     * @return list<array{name: string, color: string, description: string}>
     */
    public static function defaultDefinitions(): array
    {
        return [
            [
                'name' => CaseCategory::Kedisiplinan->value,
                'color' => 'rose',
                'description' => 'Pelanggaran tata tertib sekolah, atribut seragam, atau kedisiplinan perilaku.',
            ],
            [
                'name' => CaseCategory::Akademik->value,
                'color' => 'blue',
                'description' => 'Kesulitan belajar, penurunan nilai akademik, dan motivasi belajar siswa.',
            ],
            [
                'name' => CaseCategory::SosialPerundungan->value,
                'color' => 'purple',
                'description' => 'Konflik pertemanan, komunikasi sosial, atau kasus perundungan (bullying).',
            ],
            [
                'name' => CaseCategory::Kehadiran->value,
                'color' => 'amber',
                'description' => 'Keterlambatan hadir ke sekolah, membolos jam pelajaran, atau ketidakhadiran.',
            ],
            [
                'name' => CaseCategory::KeluargaPribadi->value,
                'color' => 'teal',
                'description' => 'Permasalahan keluarga, kesehatan mental, emosi, atau kendala pribadi siswa.',
            ],
            [
                'name' => CaseCategory::Karier->value,
                'color' => 'indigo',
                'description' => 'Konsultasi pemilihan jurusan, studi lanjut (SNBP/SNBT), dan perencanaan karier.',
            ],
            [
                'name' => CaseCategory::Lainnya->value,
                'color' => 'slate',
                'description' => 'Permasalahan atau layanan konseling di luar kategori utama.',
            ],
        ];
    }
}

