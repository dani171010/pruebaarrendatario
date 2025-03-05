<?php

namespace App\Http\Controllers;

use App\Models\post;
use App\Http\Requests\StorepostRequest;
use App\Http\Requests\UpdatepostRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(post::all());
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
    public function store(StorepostRequest $request)
    {

        if ($request->file('imagen')) {
            $file = $request->file('imagen');
            // Generar un UUID y usarlo para el nombre del archivo
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $documentoPath = $file->storeAs('posts', $filename, 'public');  // Almacena el archivo con UUID

           // Guarda la información del archivo en la base de datos
           $post = post::create([
               'titulo' => $request->titulo,
               'contenido' => $request->contenido,
               'autor_id' => $request->autor_id,
               'imagen' => $documentoPath,  // Guarda la ruta del documento
           ]);
        }
        return response()->json($post, 201);// 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(post $post)
    {
        $post->load('autor'); // Asegúrate de que la relación 'autor' esté definida en tu modelo Post
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(post $post)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepostRequest $request, post $post)
    {
        if ($request->hasFile('imagen')) {

            if ($post->imagen) {
                Storage::disk('public')->delete($post->imagen);
            }

            $file = $request->file('imagen');
            // Generar un UUID y usarlo para el nombre del archivo
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $documentoPath = $file->storeAs('posts', $filename, 'public');  // Almacena el archivo con UUID

            // Actualiza la ruta del documento en el modelo de licencia
            $post->imagen = $documentoPath;
        }

        $post->update([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'autor_id' => $request->autor_id,
        ]);

        $post->save();

        return response()->json($post, 200);// 200 OK
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        $post->delete();
        return response()->json(null, 204);// 204 No Content
    }
}
