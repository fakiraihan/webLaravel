<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::where('status', 'published')
                          ->orderBy('created_at', 'desc')
                          ->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'image_url' => 'nullable|url',
            'author_name' => 'required|string|max:255',
            'author_image' => 'nullable|url',
        ]);

        Article::create($request->all());

        return redirect()->route('admin.index')->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        // VULN: SQL Injection - for research only
        $comments = app(\App\Http\Controllers\CommentController::class)->getCommentsByArticleIdVuln($id); // $id is not sanitized
        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'image_url' => 'nullable|url',
            'author_name' => 'required|string|max:255',
            'author_image' => 'nullable|url',
        ]);

        $article->update($request->all());

        return redirect()->route('admin.index')->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.index')->with('success', 'Article deleted successfully!');
    }
}
