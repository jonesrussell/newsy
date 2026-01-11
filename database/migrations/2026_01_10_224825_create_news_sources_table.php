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
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('url');
            $table->enum('type', ['newspaper', 'tv', 'radio', 'online', 'blog', 'aggregator']);
            $table->enum('scope', ['hyperlocal', 'local', 'regional', 'provincial', 'national']);
            $table->enum('language', ['en', 'fr', 'bilingual', 'other'])->default('en');
            $table->unsignedTinyInteger('reliability_score')->nullable();
            $table->timestamp('last_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->enum('discovery_method', ['manual', 'scraped', 'api', 'user_submitted'])->default('manual');
            $table->timestamps();

            $table->index(['slug', 'type', 'scope']);
            $table->index('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
