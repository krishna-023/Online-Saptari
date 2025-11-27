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
       Schema::create('opening_times', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
    $table->boolean('displayOpeningHours')->nullable();
    $table->string('openingHoursMonday')->nullable();
    $table->string('openingHoursTuesday')->nullable();
    $table->string('openingHoursWednesday')->nullable();
    $table->string('openingHoursThursday')->nullable();
    $table->string('openingHoursFriday')->nullable();
    $table->string('openingHoursSaturday')->nullable();
    $table->string('openingHoursSunday')->nullable();
    $table->text('openingHoursNote')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_times');
    }
};
