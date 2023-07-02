<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['writer:id,username', 'comments'])->get();

        $responseData = [];
        foreach ($posts as $post) {
            $commentsData = [];
            foreach ($post->comments as $comment) {
                $commentsData[] = [
                    "id" => $comment->id,
                    "post_id" => $comment->post_id,
                    "user_id" => $comment->user_id,
                    "commentator" => $comment->commentator,
                    "comment_content" => $comment->comment_content,
                    "created_at" => $comment->created_at->format('Y/m/d H:i:s'),
                    "updated_at" => $comment->updated_at->format('Y/m/d H:i:s'),
                ];
            }

            $responseData[] = [
                'id' => $post->id,
                'title' => $post->title,
                'image' => $post->image,
                'news_content' => $post->news_content,
                'author_id' => $post->author_id,
                'author' => $post->writer->username,
                'created_at' => $post->created_at->format('Y/m/d H:i:s'),
                'comments' => $commentsData,
                'total_comments' => count($commentsData),
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $responseData,
        ]);
    }



    public function show($id)
    {
        $post = Post::with(['writer:id,username', 'comments'])->findOrFail($id);

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'image' => $post->image,
                'news_content' => $post->news_content,
                'author_id' => $post->author_id,
                'author' => $post->writer->username,
                'created_at' => date_format($post->created_at, 'd/m/Y H:i:s'),
                'comments' => $post->comments,
                'total_comments' => count($post->comments),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $fileImage = null;
        if ($request->file) {
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $fileImage = $fileName . '.' . $extension;
            Storage::putFileAs('image', $request->file, $fileImage);
        }

        $authorId = Auth::user()->id;

        $post = Post::create([
            'title' => $request->input('title'),
            'image' => $fileImage,
            'news_content' => $request->input('news_content'),
            'author_id' => $authorId
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'image' => $post->image,
                'news_content' => $post->news_content,
                'author_id' => $post->author_id,
                'created_at' => $post->created_at->format('Y/m/d H:i:s'),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'news_content' => $post->news_content,
                'author_id' => $post->author_id,
                'created_at' => $post->created_at->format('Y/m/d H:i:s'),
                'updated_at' => $post->updated_at->format('Y/m/d H:i:s'),
            ]
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'news_content' => $post->news_content,
                'author_id' => $post->author_id,
                'deleted_at' => $post->deleted_at->format('Y/m/d H:i:s'),

            ]
        ]);
    }

    function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
