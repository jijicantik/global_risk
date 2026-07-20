<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2);
            $table->integer('year');
            $table->unsignedBigInteger('gdp')->nullable();
            $table->double('inflation')->nullable();
            $table->double('currency_rate')->nullable();
            $table->double('risk_score')->nullable();
            $table->timestamps();

            $table->foreign('country_code')->references('code')->on('countries')->onDelete('cascade');
            $table->unique(['country_code', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_metrics');
    }
};
