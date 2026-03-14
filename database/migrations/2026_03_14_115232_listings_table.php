<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->unsignedInteger('price_per_day');
            $table->unsignedInteger('price_per_hour')->nullable();
            $table->unsignedInteger('deposit')->nullable();
            $table->string('city');
            $table->string('address')->nullable();
            $table->boolean('delivery_available')->default(false);
            $table->unsignedInteger('delivery_price')->nullable();
            $table->boolean('requires_document')->default(false);
            $table->enum('status', ['active', 'paused', 'archived'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
