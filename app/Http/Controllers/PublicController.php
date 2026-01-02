<?php

namespace App\Http\Controllers;

use App\Models\LinkGroup;
use App\Models\LinkGroupView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Check if link group has password protection
        if ($linkGroup->password) {
            // Check if already authenticated in session
            $sessionKey = 'link_group_auth_' . $linkGroup->id;
            
            if (!Session::has($sessionKey)) {
                // Show password prompt
                return view('public.password', compact('linkGroup'));
            }
        }

        // Increment view count
        $linkGroup->incrementViews();
        
        // Track daily view for analytics
        $today = Carbon::today();
        $viewRecord = LinkGroupView::firstOrCreate(
            [
                'link_group_id' => $linkGroup->id,
                'view_date' => $today
            ],
            [
                'view_count' => 0
            ]
        );
        
        // Increment the view count
        $viewRecord->increment('view_count');

        return view('public.show', compact('linkGroup'));
    }

    public function verifyPassword(Request $request, $slug)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $linkGroup = LinkGroup::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (!$linkGroup->password) {
            return redirect()->route('public.show', $slug);
        }

        // Verify password
        if (Hash::check($request->password, $linkGroup->password)) {
            // Store authentication in session
            $sessionKey = 'link_group_auth_' . $linkGroup->id;
            Session::put($sessionKey, true);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Access granted!',
                    'redirect' => public_route('public.show', $slug)
                ]);
            }

            return redirect()->route('public.show', $slug);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 422);
        }

        return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
    }
}
