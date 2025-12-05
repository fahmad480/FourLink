<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('dashboard.index', compact('linkGroups'));
    }
}
