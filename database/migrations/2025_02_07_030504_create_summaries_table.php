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

        Schema::create('summaries', function (Blueprint $table) {
            $table->date('month_year')->primary(); // Format disimpan sebagai '2025-02-01'
            $table->integer('total_income')->default(0);
            $table->integer('total_outcome')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
