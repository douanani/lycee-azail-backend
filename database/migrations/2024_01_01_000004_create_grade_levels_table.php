<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // 'السنة أولى جذع مشترك علوم'
            $table->string('code')->unique();     // '1SC', '2LE', '3EXP'
            $table->integer('year_number');       // 1, 2, 3
            $table->string('stream')->nullable(); // 'sciences', 'literature', 'common'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_levels');
    }
};
