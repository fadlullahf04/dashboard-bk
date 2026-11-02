<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('counseling_records', function (Blueprint $table): void {
            $table->id();
            $table->string('case_code')->unique();

            // Identitas Siswa
            $table->string('student_name')->index();
            $table->string('student_id_number', 50)->index();
            $table->string('student_class', 50)->index();
            $table->string('gender', 10)->default('L');
            $table->string('homeroom_teacher')->nullable();
            $table->string('parent_contact')->nullable();

            // Masalah / Case & Tanggal Kejadian
            $table->string('case_category')->index();
            $table->string('case_title');
            $table->text('problem_description');
            $table->string('severity', 30)->default('Sedang');
            $table->date('incident_date')->index();

            // Penanganan & Tindak Lanjut
            $table->text('handling_action');
            $table->text('follow_up')->nullable();
            $table->string('status', 40)->default('Terbuka')->index();

            // Konseling & Tanggal Konseling
            $table->date('counseling_date')->index();
            $table->text('counseling_summary');
            $table->string('counselor_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_records');
    }
};

