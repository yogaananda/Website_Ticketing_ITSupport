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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); 
            $table->string('name'); 
            $table->string('serial_number')->nullable()->unique(); 
            $table->enum('condition', ['good', 'maintenance', 'broken'])->default('good');
            $table->enum('status', ['ready', 'in_use', 'lost'])->default('ready');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->date('purchase_date')->nullable();
            $table->string('image_path')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
