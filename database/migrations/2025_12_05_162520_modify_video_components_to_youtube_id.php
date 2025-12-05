<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\LinkComponent;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete all existing video file uploads since we're switching to YouTube IDs
        $videoComponents = LinkComponent::where('type', 'video')->get();
        
        foreach ($videoComponents as $component) {
            if ($component->file_path) {
                // Delete the video file from storage
                Storage::disk('public')->delete($component->file_path);
            }
            // Clear file_path and set content to empty (user will need to re-add YouTube ID)
            $component->update([
                'file_path' => null,
                'content' => null
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse - this is a one-way migration
        // Users will need to re-upload video files if they roll back
    }
};
