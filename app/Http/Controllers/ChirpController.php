<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'message' => ['required', 'min:5', 'max:255']
        ]);

        // insertar mediante la relacion del usuario usando $request y pasando los datos en $validate
        $request->user()->chirps()->create($validate);
        /*// insertar mediante la relacion del usuario usando auth()
        auth()->user()->chirps()->create([
            'message' => $request->get('message'),
        ]);
        
        // Insrtar mediante el modelo
        Chirp::create([
            'message' => $request->get('message'),
            'user_id' => auth()->id()
        ]);*/
        return to_route('chirps.index')->with('status', 'Chirp created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        /*if (auth()->user()->isNot($chirp->user)) {
            abort(403);
        }*/
        $this->authorize('update', $chirp);

        return view('chirps.edit', ['chirp' => $chirp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        /*if (auth()->user()->isNot($chirp->user)) {
            abort(403);
        }*/
        $this->authorize('update', $chirp);

        $validate = $request->validate([
            'message' => ['required', 'min:5', 'max:255']
        ]);

        $chirp->update($validate);

        return to_route('chirps.index')
            ->with('status', __('Chirp updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return to_route('chirps.index')
            ->with('status', __('Chirp deleted successfully!'));
    }
}
