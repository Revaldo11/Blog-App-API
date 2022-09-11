<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //get all post
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'asc')->with('users:id,name,image')->withCount('comments', 'likes')
                ->with('likes', function ($like) {
                    $like->where('users_id', auth()->user()->id)
                        ->select('id', 'users_id', 'post_id', 'created_at')
                        ->join('users', 'users.id', '=', 'likes.users_id')
                        ->select('likes.*', 'users.name', 'users.image')->get();
                })
                ->get()
        ], 200);
    }

    //get singel post
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    //create post
    public function store(Request $request)
    {

        $request->validate([
            'body' => 'required|string|max:255',
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $request->body,
            'users_id' => $request->user()->id,
            'image' => $image,
        ]);

        return response([
            'message' => 'Post created successfully',
            'post' => $post
        ], 200);
    }

    //update post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        //check if post exist
        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        //check if user is owner of post
        if ($post->users_id != $request->user()->id) {
            return response([
                'message' => 'You are not owner of this post'
            ], 401);
        }

        $request->validate([
            'body' => 'required|string',
        ]);

        $post->update([
            'body' => $request->body,
        ]);

        return response([
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);

        //check if post exist
        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        //check if user is owner of post
        if ($post->users_id != $request->user()->id) {
            return response([
                'message' => 'You are not owner of this post'
            ], 401);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted successfully'
        ], 200);
    }
}
