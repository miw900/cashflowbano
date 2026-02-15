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
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->integer('total');
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->date('month_year')->foreign();    
            $table->date('tanggal_income')->foreign(); // Foreign key column
       
            $table->timestamps();

            // // Add foreign key constraint
            // $table->foreign('month_year')
            //     ->references('month_year')
            //     ->on('cash_flows')
            //     ->onDelete('cascade');

            // $table->foreign('tanggal_income')
            //     ->references('tanggal_income')
            //     ->on('cash_flows')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_outs');
    }
};
