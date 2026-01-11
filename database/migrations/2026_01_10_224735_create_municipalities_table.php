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
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('statcan_id')->nullable()->unique();
            $table->string('name');
            $table->string('name_fr')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('municipality_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('province_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('population')->nullable();
            $table->year('population_year')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('area_sq_km', 10, 2)->nullable();
            $table->string('timezone')->nullable();
            $table->json('metadata')->nullable();
            $table->string('data_source')->default('statcan');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['province_id', 'municipality_type_id']);
            $table->index('slug');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
