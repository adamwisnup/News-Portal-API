<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class userPost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentId = Auth::user();

        $post = Post::findOrFail($request->id);

        if ($post->author_id != $currentId->id) {
            return response()->json([
                'code' => 404,
                'message' => 'Not Found'
            ], 404);
        } else {
            return $next($request);
        }
    }
}
