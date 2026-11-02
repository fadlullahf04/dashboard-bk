<?php

namespace App\Http\Requests;

use App\Enums\CaseSeverity;
use App\Enums\CaseStatus;
use App\Models\CaseCategoryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCounselingRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:150'],
            'student_id_number' => ['required', 'string', 'max:50'],
            'student_class' => ['required', 'string', 'max:50'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'homeroom_teacher' => ['nullable', 'string', 'max:150'],
            'parent_contact' => ['nullable', 'string', 'max:150'],
            'case_category' => ['required', 'string', Rule::in(CaseCategoryItem::allResolved()->pluck('name')->all())],
            'case_title' => ['required', 'string', 'max:255'],
            'problem_description' => ['required', 'string'],
            'severity' => ['required', Rule::enum(CaseSeverity::class)],
            'incident_date' => ['required', 'date'],
            'handling_action' => ['required', 'string'],
            'follow_up' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(CaseStatus::class)],
            'counseling_date' => ['required', 'date', 'after_or_equal:incident_date'],
            'counseling_summary' => ['required', 'string'],
            'counselor_name' => ['required', 'string', 'max:150'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'student_name' => 'nama siswa',
            'student_id_number' => 'NIS/NISN',
            'student_class' => 'kelas',
            'gender' => 'jenis kelamin',
            'homeroom_teacher' => 'wali kelas',
            'parent_contact' => 'kontak orang tua/wali',
            'case_category' => 'kategori masalah/case',
            'case_title' => 'judul masalah/case',
            'problem_description' => 'kronologi/deskripsi masalah',
            'severity' => 'tingkat urgensi',
            'incident_date' => 'tanggal kejadian',
            'handling_action' => 'penanganan',
            'follow_up' => 'tindak lanjut',
            'status' => 'status penanganan',
            'counseling_date' => 'tanggal konseling',
            'counseling_summary' => 'rangkuman konseling',
            'counselor_name' => 'guru BK / konselor',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'counseling_date.after_or_equal' => 'Tanggal konseling harus sama dengan atau setelah tanggal kejadian.',
        ];
    }
}

