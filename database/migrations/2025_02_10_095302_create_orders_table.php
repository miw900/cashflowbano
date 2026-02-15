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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('harga');
            $table->text('catatan')->nullable();
            $table->time('jam_ambil');
            $table->date('tanggal_ambil');
            $table->string('transaksi')->nullable();
            $table->string('status')->default('pending');
            $table->date('tanggal_income')->foreign(); // Foreign key column

            $table->timestamps();

            // // Add foreign key constraint
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
        Schema::dropIfExists('orders');
    }
};
