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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id')->nullable(); // Ensure this line exists
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('content')->nullable();
            $table->string('item_featured')->nullable();
            $table->date('collection_date')->nullable();
            $table->string('permalink')->nullable();
            $table->string('image')->nullable();
            $table->string('author_username')->nullable();
            $table->string('author_email')->nullable();
            $table->string('author_first_name')->nullable();
            $table->string('author_last_name')->nullable();
            $table->string('slug');
            $table->string('parent')->nullable(); // Changed from varchar to string and made nullable
            $table->string('parent_slug')->nullable(); // Changed from varchar to string and made nullable
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Added category_id with foreign key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
