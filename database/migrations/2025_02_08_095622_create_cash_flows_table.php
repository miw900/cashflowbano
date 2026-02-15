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
       Schema::create('incomes', function (Blueprint $table) {
            $table->integer('gopay')->default(0);
            $table->integer('cash')->default(0);
            $table->integer('bsi')->default(0);
            $table->integer('total');
            $table->integer('total_outcome');
            $table->date('tanggal_income')->primary()->unique();
            $table->date('month_year')->foreign();       
            $table->timestamps();

            // Add foreign key constraint
            // $table->foreign('month_year')
            //     ->references('month_year')
            //     ->on('cash_flows')
            //     ->onDelete('cascade');
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
