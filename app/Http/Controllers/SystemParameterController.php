<?php

namespace App\Http\Controllers;

use App\Models\SystemParameter;
use Illuminate\Http\Request;

class SystemParameterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // You can add admin middleware here if needed
        // $this->middleware('role:admin');
    }

    /**
     * Display a listing of the system parameters.
     */
    public function index()
    {
        $parameters = SystemParameter::orderBy('code')->paginate(15);
        return view('system-parameters.index', compact('parameters'));
    }

    /**
     * Show the form for creating a new system parameter.
     */
    public function create()
    {
        return view('system-parameters.create');
    }

    /**
     * Store a newly created system parameter in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:system_parameters,code',
            'value' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Handle "set null" checkbox
        if ($request->has('set_null')) {
            $validated['value'] = null;
        }

        SystemParameter::create($validated);

        return redirect()->route('admin.system-parameters.index')
            ->with('success', 'System parameter created successfully!');
    }

    /**
     * Display the specified system parameter.
     */
    public function show(SystemParameter $systemParameter)
    {
        return view('system-parameters.show', compact('systemParameter'));
    }

    /**
     * Show the form for editing the specified system parameter.
     */
    public function edit(SystemParameter $systemParameter)
    {
        return view('system-parameters.edit', compact('systemParameter'));
    }

    /**
     * Update the specified system parameter in storage.
     */
    public function update(Request $request, SystemParameter $systemParameter)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:system_parameters,code,' . $systemParameter->id,
            'value' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Handle "set null" checkbox
        if ($request->has('set_null')) {
            $validated['value'] = null;
        }

        $systemParameter->update($validated);

        return redirect()->route('admin.system-parameters.index')
            ->with('success', 'System parameter updated successfully!');
    }

    /**
     * Remove the specified system parameter from storage.
     */
    public function destroy(SystemParameter $systemParameter)
    {
        $systemParameter->delete();

        return redirect()->route('admin.system-parameters.index')
            ->with('success', 'System parameter deleted successfully!');
    }

    /**
     * Set the value of a system parameter to null
     */
    public function setNull(SystemParameter $systemParameter)
    {
        $systemParameter->update(['value' => null]);

        return redirect()->route('admin.system-parameters.index')
            ->with('success', 'System parameter value set to null successfully!');
    }
}
