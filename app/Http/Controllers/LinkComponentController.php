<?php

namespace App\Http\Controllers;

use App\Models\LinkGroup;
use App\Models\LinkComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LinkComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(LinkGroup $linkGroup)
    {
        $this->authorize('update', $linkGroup);
        
        return view('components.create', compact('linkGroup'));
    }

    public function store(Request $request, LinkGroup $linkGroup)
    {
        $this->authorize('update', $linkGroup);

        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', LinkComponent::getTypes()),
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB
            'order' => 'nullable|integer',
        ]);

        $validated['link_group_id'] = $linkGroup->id;

        // Handle file upload for image, video, and file types
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $type = $validated['type'];
            
            // Determine storage folder based on type
            $folder = match($type) {
                'image' => 'images',
                'video' => 'videos',
                'file' => 'files',
                default => 'uploads'
            };

            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, $filename, 'public');
            $validated['file_path'] = $path;
        }

        // Set default order if not provided
        if (!isset($validated['order'])) {
            $maxOrder = $linkGroup->components()->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        $component = LinkComponent::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Component added successfully!',
                'component' => $component
            ]);
        }

        return redirect()->route('link-groups.show', $linkGroup->slug)
            ->with('success', 'Component added successfully!');
    }

    public function edit(LinkGroup $linkGroup, LinkComponent $component)
    {
        $this->authorize('update', $linkGroup);
        
        if ($component->link_group_id !== $linkGroup->id) {
            abort(404);
        }

        return view('components.edit', compact('linkGroup', 'component'));
    }

    public function update(Request $request, LinkGroup $linkGroup, LinkComponent $component)
    {
        $this->authorize('update', $linkGroup);

        if ($component->link_group_id !== $linkGroup->id) {
            abort(404);
        }

        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', LinkComponent::getTypes()),
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($component->file_path) {
                Storage::disk('public')->delete($component->file_path);
            }

            $file = $request->file('file');
            $type = $validated['type'];
            
            $folder = match($type) {
                'image' => 'images',
                'video' => 'videos',
                'file' => 'files',
                default => 'uploads'
            };

            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, $filename, 'public');
            $validated['file_path'] = $path;
        }

        $component->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Component updated successfully!',
                'component' => $component
            ]);
        }

        return redirect()->route('link-groups.show', $linkGroup->slug)
            ->with('success', 'Component updated successfully!');
    }

    public function destroy(LinkGroup $linkGroup, LinkComponent $component)
    {
        $this->authorize('update', $linkGroup);

        if ($component->link_group_id !== $linkGroup->id) {
            abort(404);
        }

        // Delete file if exists
        if ($component->file_path) {
            Storage::disk('public')->delete($component->file_path);
        }

        $component->delete();

        return response()->json([
            'success' => true,
            'message' => 'Component deleted successfully!'
        ]);
    }

    public function reorder(Request $request, LinkGroup $linkGroup)
    {
        $this->authorize('update', $linkGroup);

        $validated = $request->validate([
            'components' => 'required|array',
            'components.*.id' => 'required|exists:link_components,id',
            'components.*.order' => 'required|integer',
        ]);

        foreach ($validated['components'] as $componentData) {
            LinkComponent::where('id', $componentData['id'])
                ->where('link_group_id', $linkGroup->id)
                ->update(['order' => $componentData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Components reordered successfully!'
        ]);
    }
}
