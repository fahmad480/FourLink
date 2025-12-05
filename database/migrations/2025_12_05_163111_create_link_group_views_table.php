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
        Schema::create('link_group_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_group_id')->constrained()->onDelete('cascade');
            $table->date('view_date');
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
            
            // Unique constraint to ensure one record per link_group per day
            $table->unique(['link_group_id', 'view_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_group_views');
    }
};
