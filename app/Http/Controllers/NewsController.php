<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori;

class NewsController extends Controller
{
    // Display a listing of the resource
    public function index() 
    {
        $news = News::with(['kategori', 'user'])->paginate(10);
        $categories = Kategori::all();
        return view('news.news', compact('news', 'categories'));
    }

    // Show the form for creating a new resource (not used because we use modal)
    public function create()
    {
        //
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'approve' => 'required|in:y,n',
            'category_id' => 'required|exists:kategoris,id',
        ]);

        // Handle file upload
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = $file->store('uploads/news', 'public');
            $validatedData['image'] = $path;
        }

        // Assign authenticated user
        $validatedData['user_id'] = Auth::id();

        // Create news
        $news = News::create($validatedData);

        return response()->json(['status' => 'success', 'message' => 'News created successfully.', 'data' => $news]);
    }

    // Display the specified resource (not used because we use modal)
    public function show(News $news)
    {
        //
    }

    // Show the form for editing the specified resource (AJAX)
    public function edit(News $news)
    {
        $categories = Kategori::all();
        return response()->json([
            'categories' => $categories,
            'news' => $news
        ]);
    }

    // POST to update (not using PUT)
    public function postUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'approve' => 'required|in:y,n',
            'category_id' => 'required|exists:kategoris,id',
        ]);

        $news = News::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = $file->store('uploads/news', 'public');
            $validatedData['image'] = $path;
        }

        $news->update($validatedData);

        return response()->json(['status' => 'success', 'message' => 'News updated successfully.', 'data' => $news]);
    }

    // Remove the specified resource from storage
    public function destroy(News $news)
    {
        if ($news) {
            $news->delete();
            return response()->json(['status' => 'success', 'message' => 'News deleted successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'News not found.']);
    }
}
