<?php

namespace App\Http\Controllers;

use App\Models\LinkGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LinkGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Always show only user's own links, regardless of role
        $linkGroups = $user->linkGroups()->withCount('components')->latest()->paginate(15);

        return view('link-groups.index', compact('linkGroups'));
    }

    public function create()
    {
        return view('link-groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'background_color' => 'required|string|max:7',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6',
            'instagram_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'x_url' => 'nullable|url',
            'threads_url' => 'nullable|url',
            'website_url' => 'nullable|url',
        ]);

        $validated['user_id'] = auth()->id();

        // Hash password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('thumbnails', $filename, 'public');
            $validated['thumbnail'] = $path;
        }

        $linkGroup = LinkGroup::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Link group created successfully!',
                'redirect' => route('link-groups.show', $linkGroup->slug)
            ]);
        }

        return redirect()->route('link-groups.show', $linkGroup->slug)
            ->with('success', 'Link group created successfully!');
    }

    public function show(LinkGroup $linkGroup)
    {
        $this->authorize('view', $linkGroup);
        
        $linkGroup->load(['components' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }]);

        return view('link-groups.show', compact('linkGroup'));
    }

    public function edit(LinkGroup $linkGroup)
    {
        $this->authorize('update', $linkGroup);
        
        return view('link-groups.edit', compact('linkGroup'));
    }

    public function update(Request $request, LinkGroup $linkGroup)
    {
        $this->authorize('update', $linkGroup);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'background_color' => 'required|string|max:7',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:6',
            'instagram_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'x_url' => 'nullable|url',
            'threads_url' => 'nullable|url',
            'website_url' => 'nullable|url',
        ]);

        // Hash password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            // Don't update password if field is empty
            unset($validated['password']);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($linkGroup->thumbnail) {
                Storage::disk('public')->delete($linkGroup->thumbnail);
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('thumbnails', $filename, 'public');
            $validated['thumbnail'] = $path;
        }

        $linkGroup->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Link group updated successfully!',
                'redirect' => route('link-groups.show', $linkGroup->slug)
            ]);
        }

        return redirect()->route('link-groups.show', $linkGroup->slug)
            ->with('success', 'Link group updated successfully!');
    }

    public function destroy(LinkGroup $linkGroup)
    {
        $this->authorize('delete', $linkGroup);

        // Delete thumbnail
        if ($linkGroup->thumbnail) {
            Storage::disk('public')->delete($linkGroup->thumbnail);
        }

        // Delete all component files
        foreach ($linkGroup->components as $component) {
            if ($component->file_path) {
                Storage::disk('public')->delete($component->file_path);
            }
        }

        $linkGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Link group deleted successfully!',
            'redirect' => route('link-groups.index')
        ]);
    }
}
