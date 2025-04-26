<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori;

class NewsController extends Controller
{
    // Display a listing of the resource
    public function index(Request $request)
    {
        $query = News::query();

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Limit access for non-admin users
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Pagination and eager loading
        $news = $query->with('kategori')->latest()->paginate(10);

        return view('news.news', compact('news'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        // Load categories for selection
        $categories = Kategori::all();
        return view('news.create', compact('categories')); 
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string|max:255',
            'approve' => 'required|in:y,n',
            'user_id' => 'required|exists:users,id',
            'category' => 'required|exists:kategoris,id',
        ]);

        News::create($validatedData);

        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    // Display the specified resource
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    // Show the form for editing the specified resource
    public function edit(News $news)
    {
        $categories = Kategori::all();
        return view('news.edit', compact('news', 'categories'));
    }

    // Update the specified resource in storage
    public function update(Request $request, News $news)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string|max:255',
            'approve' => 'required|in:y,n',
            'user_id' => 'required|exists:users,id',
            'category' => 'required|exists:kategoris,id',
        ]);

        $news->update($validatedData);

        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')->with('success', 'News deleted successfully.');
    }
}