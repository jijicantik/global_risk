<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2)->unique();
            $table->double('weather_score')->default(0);
            $table->double('inflation_score')->default(0);
            $table->double('currency_score')->default(0);
            $table->double('news_sentiment_score')->default(0);
            $table->double('total_score')->default(0);
            $table->string('risk_level')->default('Low'); // Low, Medium, High
            $table->timestamps();

            $table->foreign('country_code')->references('code')->on('countries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
