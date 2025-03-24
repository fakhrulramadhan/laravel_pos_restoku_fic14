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
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->integer('table_number')->nullable(); // kolom utk nomor meja
            $table->string('customer_name')->nullable(); //kolom untuk nama customer
            $table->string('status')->nullable(); //kolom utk status order (default pending)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropColumn('table_number');
            $table->dropColumn('customer_name');
            $table->dropColumn('status');
        });
    }
};
