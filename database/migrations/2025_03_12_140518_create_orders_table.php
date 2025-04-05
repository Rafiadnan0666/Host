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
            $table->unsignedBigInteger("pemesan_id");
            $table->foreign("pemesan_id")->references("id")->on("users");
        
            $table->unsignedBigInteger("pesan_id");
            $table->foreign("pesan_id")->references("id")->on("users");
        
            $table->datetime("waktu");
            $table->float("cost");
            $table->enum('approve', ['y', 'n']);
            $table->datetime("batas");
            $table->string("location");
        
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
            $table->timestamps();
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
