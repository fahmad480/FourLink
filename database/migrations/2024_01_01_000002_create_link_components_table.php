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
        Schema::create('link_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_group_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['link', 'image', 'embed', 'text', 'video', 'file']);
            $table->string('title')->nullable();
            $table->text('content')->nullable(); // URL, embed code, text content, etc.
            $table->string('file_path')->nullable(); // for image, video, file uploads
            $table->text('metadata')->nullable(); // JSON field for additional data
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_components');
    }
};
