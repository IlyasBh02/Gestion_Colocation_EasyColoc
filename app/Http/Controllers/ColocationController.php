<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ColocationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $colocations = Colocation::with('owner')->get();
        return view('colocations.index', compact('colocations'));
    }

    public function create()
    {
        return view('colocations.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasActiveMembership()) {
            return back()->with('error', 'You cannot create a new colocation while you have an active membership.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $colocation = Auth::user()->ownedColocations()->create($validated);

        return redirect()->route('colocations.index')->with('success', 'Colocation created successfully.');
    }

    public function show(Request $request, Colocation $colocation)
    {
        $month = $request->get('month', now()->format('Y-m'));
        
        $expenses = $colocation->expenses()
            ->with(['category', 'payer'])
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->orderBy('date', 'desc')
            ->get();
        
        $categories = \App\Models\Category::all();
        
        return view('colocations.show', compact('colocation', 'expenses', 'categories', 'month'));
    }

    public function edit(Colocation $colocation)
    {
        $this->authorize('update', $colocation);
        return view('colocations.edit', compact('colocation'));
    }

    public function update(Request $request, Colocation $colocation)
    {
        $this->authorize('update', $colocation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $colocation->update($validated);

        return redirect()->route('colocations.index')->with('success', 'Colocation updated successfully.');
    }

    public function destroy(Colocation $colocation)
    {
        $this->authorize('delete', $colocation);

        $colocation->delete();

        return redirect()->route('colocations.index')->with('success', 'Colocation deleted successfully.');
    }
}
