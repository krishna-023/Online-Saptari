<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->text('image_url');
            $table->string('image_path')->nullable();
            $table->boolean('display_gallery')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('original_filename')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('file_extension')->nullable();
            $table->enum('download_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('download_error')->nullable();
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamps();

            $table->index('item_id');
            $table->index('download_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
