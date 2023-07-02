<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment_content' => 'required',
        ]);

        $currentId = Auth::user()->id;

        $comment = Comment::with('commentator:id,username')->create([
            'post_id' => $request->post_id,
            'user_id' => $currentId,
            'comment_content' => $request->comment_content
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                "id" => $comment->id,
                "post_id" => $comment->post_id,
                "user_id" => $comment->user_id,
                "commentator" => $comment->commentator->username,
                "comment_content" => $comment->comment_content,
                'created_at' => $comment->created_at->format('Y/m/d H:i:s'),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment_content' => 'required'
        ]);

        $comment = Comment::with('commentator:id,username')->findOrFail($id);
        $comment->update($request->only('comment_content'));

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                "id" => $comment->id,
                "post_id" => $comment->post_id,
                "user_id" => $comment->user_id,
                "commentator" => $comment->commentator->username,
                "comment_content" => $comment->comment_content,
                'created_at' => $comment->created_at->format('Y/m/d H:i:s'),
                'updated_at' => $comment->updated_at->format('Y/m/d H:i:s'),
            ]
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            "data" => [
                "id" => $comment->id,
                "post_id" => $comment->post_id,
                "user_id" => $comment->user_id,
                "commentator" => $comment->commentator->username,
                "comment_content" => $comment->comment_content,
                'deleted_at' => $comment->deleted_at->format('Y/m/d H:i:s'),
            ]
        ]);
    }
}
