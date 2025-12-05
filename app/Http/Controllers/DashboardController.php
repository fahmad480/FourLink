<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LinkGroup;
use App\Models\LinkComponent;
use App\Models\LinkGroupView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $linkGroups = $user->linkGroups()->withCount('components')->latest()->paginate(10);
        
        // Get statistics
        $stats = [
            'total_link_groups' => $user->linkGroups()->count(),
            'total_components' => LinkComponent::whereHas('linkGroup', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'total_views' => $user->linkGroups()->sum('views_count'),
            'active_links' => $user->linkGroups()->where('is_active', true)->count(),
        ];
        
        // Get top 5 link groups by views
        $topLinkGroups = $user->linkGroups()
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get(['title', 'views_count', 'slug']);
        
        // Get views over last 30 days for chart
        $viewsData = LinkGroupView::whereHas('linkGroup', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('view_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->select('view_date', DB::raw('SUM(view_count) as total_views'))
            ->get();

        return view('dashboard.index', compact('linkGroups', 'stats', 'topLinkGroups', 'viewsData'));
    }
}
