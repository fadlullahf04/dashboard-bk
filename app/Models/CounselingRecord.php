<?php

namespace App\Models;

use App\Casts\CaseCategoryCast;
use App\Enums\CaseCategory;
use App\Enums\CaseSeverity;
use App\Enums\CaseStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CounselingRecord extends Model
{
    /** @use HasFactory<\Database\Factories\CounselingRecordFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'case_code',
        'student_name',
        'student_id_number',
        'student_class',
        'gender',
        'homeroom_teacher',
        'parent_contact',
        'case_category',
        'case_title',
        'problem_description',
        'severity',
        'incident_date',
        'handling_action',
        'follow_up',
        'status',
        'counseling_date',
        'counseling_summary',
        'counselor_name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'case_category' => CaseCategoryCast::class,
            'severity' => CaseSeverity::class,
            'status' => CaseStatus::class,
            'incident_date' => 'date',
            'counseling_date' => 'date',
        ];
    }

    /**
     * Boot the model to automatically generate a unique case_code when creating.
     */
    protected static function booted(): void
    {
        static::creating(function (CounselingRecord $record): void {
            if (empty($record->case_code)) {
                $datePart = ($record->incident_date instanceof Carbon ? $record->incident_date : Carbon::now())->format('Ym');
                $randomPart = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
                $record->case_code = "BK-{$datePart}-{$randomPart}";
            }
        });
    }

    /**
     * Scope a query to filter counseling records based on search parameters.
     *
     * @param  Builder<CounselingRecord>  $query
     * @param  array{search?: string|null, category?: string|null, date?: string|null, month?: string|null, status?: string|null, severity?: string|null, class?: string|null, date_field?: string|null}  $filters
     * @return Builder<CounselingRecord>
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $dateColumn = ($filters['date_field'] ?? 'incident_date') === 'counseling_date'
            ? 'counseling_date'
            : 'incident_date';

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function (Builder $subQuery) use ($search): void {
                $subQuery->where('student_name', 'like', "%{$search}%")
                    ->orWhere('student_id_number', 'like', "%{$search}%")
                    ->orWhere('student_class', 'like', "%{$search}%")
                    ->orWhere('case_code', 'like', "%{$search}%")
                    ->orWhere('case_title', 'like', "%{$search}%")
                    ->orWhere('problem_description', 'like', "%{$search}%")
                    ->orWhere('counseling_summary', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('case_category', $filters['category']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        if (! empty($filters['class'])) {
            $query->where('student_class', $filters['class']);
        }

        if (! empty($filters['date'])) {
            $query->whereDate($dateColumn, $filters['date']);
        }

        if (! empty($filters['month']) && preg_match('/^\d{4}-\d{2}$/', (string) $filters['month'])) {
            [$year, $month] = explode('-', (string) $filters['month']);
            $query->whereYear($dateColumn, (int) $year)
                ->whereMonth($dateColumn, (int) $month);
        }

        return $query;
    }
}

