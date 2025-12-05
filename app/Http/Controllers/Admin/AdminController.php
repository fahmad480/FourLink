<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LinkGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalLinkGroups = LinkGroup::count();
        $totalViews = LinkGroup::sum('views_count');
        
        $recentUsers = User::latest()->take(5)->get();
        $recentLinkGroups = LinkGroup::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalLinkGroups',
            'totalViews',
            'recentUsers',
            'recentLinkGroups'
        ));
    }

    public function users()
    {
        $users = User::with('roles')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'redirect' => route('admin.users')
            ]);
        }

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        $user->syncRoles([$validated['role']]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'redirect' => route('admin.users')
            ]);
        }

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
    }

    public function destroyUser(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account!'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }

    public function linkGroups()
    {
        $linkGroups = LinkGroup::with(['user', 'components'])
            ->withCount('components')
            ->latest()
            ->paginate(15);

        return view('admin.link-groups', compact('linkGroups'));
    }
}
