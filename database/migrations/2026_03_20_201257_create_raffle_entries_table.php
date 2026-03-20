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
        Schema::create('raffle_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raffle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('ticket_count')->default(1);
            $table->enum('payment_status', ['pending', 'confirmed'])->default('pending');
            $table->timestamps();

            $table->unique(['raffle_id', 'user_id']);
        
        });

        DB::statement('ALTER TABLE raffle_entries ADD CONSTRAINT chk_ticket_count CHECK (ticket_count BETWEEN 1 AND 3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffle_entries');
    }
};
