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
        Schema::create('currencies_countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('country_id');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies_countries');
    }
};
