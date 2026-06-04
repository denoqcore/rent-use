<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('listing_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('renter_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('total_price');
            $table->unsignedInteger('deposit')->nullable();
            $table->enum('currency', ['MDL', 'EUR', 'USD']);

            $table->boolean('hidden_for_renter')->default(false);
            $table->boolean('hidden_for_owner')->default(false);

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending');
            $table->text('cancel_reason')->nullable();
            $table->enum('cancelled_by', ['renter', 'owner', 'system'])->nullable();

            $table->timestamps();
            $table->index(['renter_id', 'status']);
            $table->index(['owner_id', 'status']);
            $table->index(['listing_id', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
