<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ============================================================
// MIGRATION: 2024_01_01_000010_create_announcements_table.php
// ============================================================
return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();             // author
            $table->string('title');
            $table->text('body');
            // Target audience category
            $table->enum('audience', ['all', 'teachers', 'students', 'parents', 'guidance']);
            // Which role is allowed to publish this type
            $table->enum('published_by_role', ['admin', 'counselor', 'admin_staff', 'supervisor']);
            $table->string('attachment_path')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['audience', 'is_published']);
        });
    }
    public function down(): void { Schema::dropIfExists('announcements'); }
};