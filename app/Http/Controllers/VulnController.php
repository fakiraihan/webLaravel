<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VulnController extends Controller
{
    /**
     * VULN: Direct SQL injection endpoint for brutal vulnerability testing
     * No sanitization, no protection - INTENTIONAL for security research!
     */
    public function searchVuln($search)
    {
        // BRUTAL SQLi - direct query with user input
        $query = "SELECT * FROM articles WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
        
        try {
            $results = DB::select($query);
            return view('search_results', [
                'search' => $search,
                'results' => $results,
                'error' => null
            ]);
        } catch (\Exception $e) {
            return view('search_results', [
                'search' => $search,
                'results' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * VULN: User enumeration with SQL injection
     * Completely unsafe - INTENTIONAL for research!
     */
    public function getUserVuln($userId)
    {
        // BRUTAL SQLi - direct user ID injection
        $query = "SELECT * FROM users WHERE id = $userId";
        
        try {
            $user = DB::select($query);
            return response()->json([
                'user' => $user,
                'query' => $query,
                'vulnerability' => 'Direct SQL injection - no protection!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'query' => $query,
                'vulnerability' => 'SQLi error exposed!'
            ]);
        }
    }

    /**
     * VULN: Comment deletion without authorization
     * IDOR vulnerability - anyone can delete any comment
     */
    public function deleteComment($commentId)
    {
        try {
            // BRUTAL IDOR - no auth check!
            $deleted = DB::delete("DELETE FROM comments WHERE id = $commentId");
            
            return response()->json([
                'message' => "Comment $commentId deleted without authorization check!",
                'deleted' => $deleted,
                'vulnerability' => 'IDOR - Insecure Direct Object Reference'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'vulnerability' => 'IDOR with error disclosure'
            ]);
        }
    }
}
