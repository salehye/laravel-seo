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
        Schema::create(config('seo.seo_table', 'seo_metadata'), function (Blueprint $table) {
            $table->id();

            // Model information
            $table->string('model_type'); // The model class name
            $table->unsignedBigInteger('model_id'); // The model ID
            $table->string('model_key')->nullable(); // Optional key/slug for lookup

            // Basic SEO fields
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Images
            $table->string('og_image')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('canonical_url')->nullable();

            // Robots settings
            $table->string('robots')->default('index, follow');
            $table->boolean('no_index')->default(false);
            $table->boolean('no_follow')->default(false);

            // Schema/Structured data (JSON)
            $table->json('schema_data')->nullable();

            // Open Graph data (JSON)
            $table->json('og_data')->nullable();

            // Twitter Card data (JSON)
            $table->json('twitter_data')->nullable();

            // Additional meta tags (JSON)
            $table->json('additional_meta')->nullable();

            // Localization
            $table->string('locale')->default('en');
            $table->string('language')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes for performance
            $table->unique(['model_type', 'model_id', 'locale']);
            $table->index('model_key');
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('seo.seo_table', 'seo_metadata'));
    }
};
