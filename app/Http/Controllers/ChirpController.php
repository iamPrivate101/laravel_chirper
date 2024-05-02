<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $chirps = Chirp::with('user')->latest()->paginate(5);
        // dd($chirps);
        return view('chirps.index',[
            'chirps' => $chirps,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $request->user()->chirps()->create($validated);

        flash()->addSuccess('New Chirp Created Sucessfully!!!');

        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        dd($chirp);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View 
    {
        // dd($chirp);
        Gate::authorize('update', $chirp);

        return view('chirps.edit',[
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);

        $validated= $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        flash()->addSuccess('Chirp Updated Sucessfully!!!');

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        // Gate::authorize('delete', $chirp);'
        // Route model binding

        $chirp->delete();
        $data = [
            'message' => "Deleted Successfully",
            'status' => 200
        ];
        return  response()->json($data, 200);

        // flash()->addSuccess('Chirp Deleted Sucessfully!!!');

        // return redirect(route('chirps.index'));
    }
}
