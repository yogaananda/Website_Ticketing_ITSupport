<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // Tambahan
            $table->string('full_name');          // Tambahan
            $table->string('division')->nullable(); // Tambahan
            $table->enum('role', ['admin', 'it_support', 'user'])->default('user'); // Tambahan
            $table->string('email')->unique(); // Bawaan
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
