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
            $table->string('username')->unique(); 
            $table->string('full_name');          
            $table->string('division')->nullable(); 
            $table->enum('role', ['admin', 'it_support', 'user'])->default('user'); 
            $table->string('email')->unique(); 
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
