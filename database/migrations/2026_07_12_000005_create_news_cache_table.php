<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2);
            $table->string('title', 500);
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('url', 1000)->nullable();
            $table->string('source')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->integer('sentiment_positive')->default(0);
            $table->integer('sentiment_negative')->default(0);
            $table->string('sentiment_label')->default('Neutral');
            $table->timestamps();

            $table->foreign('country_code')->references('code')->on('countries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_cache');
    }
};
