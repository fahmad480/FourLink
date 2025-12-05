<?php

namespace App\Http\Controllers;

use App\Models\LinkGroup;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function show($slug)
    {
        $linkGroup = LinkGroup::where('slug', $slug)
            ->where('is_active', true)
            ->with(['components' => function($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->firstOrFail();

        // Increment view count
        $linkGroup->incrementViews();

        return view('public.show', compact('linkGroup'));
    }
}
