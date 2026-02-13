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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('ticket_id')->nullable()->constrained('tickets'); 
            $table->string('item_name'); 
            $table->text('description')->nullable(); 
            $table->integer('quantity')->default(1);
            $table->decimal('estimated_price', 15, 2)->nullable(); 
            $table->string('link_reference')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['pending', 'approved', 'rejected', 'purchased'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
