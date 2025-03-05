<?php

namespace App\Http\Controllers;

use App\Models\autor;
use App\Http\Requests\StoreautorRequest;
use App\Http\Requests\UpdateautorRequest;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(autor::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreautorRequest $request)
    {
        $autor = autor::create($request->all());
        return response()->json($autor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(autor $autor)
    {
        return response()->json($autor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(autor $autor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateautorRequest $request, autor $autor)
    {
        $autor->update($request->all());
        return response()->json($autor, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(autor $autor)
    {
        $autor->delete();
        return response()->json(null, 204);
    }
}
