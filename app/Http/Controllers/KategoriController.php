<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategoris.index', compact('kategoris')); // Make sure you create 'kategoris.index' view
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('kategoris.create'); // Make sure you create 'kategoris.create' view
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        Kategori::create($validatedData);

        return redirect()->route('kategoris.index')->with('success', 'Kategori created successfully.');
    }

    // Display the specified resource
    public function show(Kategori $kategori)
    {
        return view('kategoris.show', compact('kategori')); // Make sure you create 'kategoris.show' view
    }

    // Show the form for editing the specified resource
    public function edit(Kategori $kategori)
    {
        return view('kategoris.edit', compact('kategori')); // Make sure you create 'kategoris.edit' view
    }

    // Update the specified resource in storage
    public function update(Request $request, Kategori $kategori)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori->update($validatedData);

        return redirect()->route('kategoris.index')->with('success', 'Kategori updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('kategoris.index')->with('success', 'Kategori deleted successfully.');
    }
}
