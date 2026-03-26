<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_suburb')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->foreignId('city_id')->after('category_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
            $table->string('city')->after('category_id');
        });
        Schema::dropIfExists('cities');
    }
};
