<?php
namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $articleId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $article = Article::findOrFail($articleId);
        
        // VULN: No authentication check - anyone can comment!
        // Always use admin user ID since no auth
        $comment = new Comment([
            'content' => $request->content,
            'user_id' => 1, // Always use admin user ID - BRUTAL!
        ]);
        $article->comments()->save($comment);
        return redirect()->back()->with('success', 'Comment posted without authentication!');
    }

    // VULN: SQL Injection - for research only
    public function getCommentsByArticleIdVuln($articleId)
    {
        // Directly interpolate user input (no binding)
        return \DB::select("SELECT * FROM comments WHERE article_id = $articleId ORDER BY created_at ASC");
    }
}
