<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        return view('admin.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'image_url' => 'nullable|url',
            'author_name' => 'required|string|max:255',
            'author_image' => 'nullable|url',
            'status' => 'required|in:published,draft',
        ]);

        Article::create($request->all());

        return redirect()->route('admin.index')->with('success', 'Article created successfully!');
    }

    public function edit(Article $article)
    {
        return view('admin.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'image_url' => 'nullable|url',
            'author_name' => 'required|string|max:255',
            'author_image' => 'nullable|url',
            'status' => 'required|in:published,draft',
        ]);

        $article->update($request->all());

        return redirect()->route('admin.index')->with('success', 'Article updated successfully!');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.index')->with('success', 'Article deleted successfully!');
    }
}
