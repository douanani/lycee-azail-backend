<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ============================================================
// MIGRATION: 2024_01_01_000009_create_resources_table.php
// (Unified table for Lessons, Exams, Homework via `type` discriminator)
// ============================================================
return new class extends Migration {
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();             // uploader
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->foreignId('grade_level_id')->constrained()->restrictOnDelete();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();

            // Discriminator column
            $table->enum('type', ['lesson', 'exam', 'homework', 'guide']);

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');               // Storage path
            $table->string('file_name');               // Original filename
            $table->string('file_type');               // 'pdf', 'docx', etc.
            $table->unsignedBigInteger('file_size');   // Bytes
            $table->string('semester')->nullable();    // 'الفصل الأول', 'الفصل الثاني', 'الفصل الثالث'
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index(['type', 'grade_level_id', 'subject_id', 'academic_year_id']);
            $table->index(['user_id', 'type']);
            $table->index('is_published');
        });
    }
    public function down(): void { Schema::dropIfExists('resources'); }
};

