<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $news = News::all(); // Retrieve all news entries
        return view('news.index', compact('news')); // Ensure you create a 'news.index' view
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('news.create'); // Ensure you create a 'news.create' view
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|string|max:255',
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
        return view('news.show', compact('news')); // Ensure you create a 'news.show' view
    }

    // Show the form for editing the specified resource
    public function edit(News $news)
    {
        return view('news.edit', compact('news')); // Ensure you create a 'news.edit' view
    }

    // Update the specified resource in storage
    public function update(Request $request, News $news)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|string|max:255',
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
