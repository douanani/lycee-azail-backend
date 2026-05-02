<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // 'رياضيات'
            $table->string('name_fr')->nullable(); // 'Mathématiques'
            $table->string('code')->unique();      // 'MATH', 'PHY'
            $table->string('icon')->nullable();    // 'bi-calculator-fill'
            $table->string('color')->nullable();   // '#4f46e5'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
