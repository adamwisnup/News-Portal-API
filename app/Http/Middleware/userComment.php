<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class userComment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentId = Auth::user();

        $comment = Comment::findOrFail($request->id);

        if ($comment->user_id != $currentId->id) {
            return response()->json([
                'status' => 404,
                'message' => 'Not Found'
            ], 404);
        } else {
            return $next($request);
        }
    }
}
