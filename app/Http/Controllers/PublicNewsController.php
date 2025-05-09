<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Str;

class PublicNewsController extends Controller
{
    public function index()
    {
        $news = News::where('approve', 'y')->latest()->get();
        return view('news.newspage', compact('news'));
    }

    public function show($slug)
    {
        $news = News::whereRaw('LOWER(REPLACE(title, " ", "-")) = ?', [$slug])
                    ->where('approve', 'y')
                    ->firstOrFail();

        $related = News::where('category_id', $news->category_id)
                        ->where('id', '!=', $news->id)
                        ->where('approve', 'y')
                        ->latest()
                        ->take(4)
                        ->get();

        return view('news.single', compact('news', 'related'));
    }
}
