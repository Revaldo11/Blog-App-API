<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //get all comment of a post
    public function index($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        return response([
            'comments' => $post->comments()->with('users:id,name,image')->get()
        ], 200);
    }

    //create comment on a post
    public function store(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $comment = Comment::create([
            'comment' => $request->comment,
            'users_id' => $request->user()->id,
            'post_id' => $id,
        ]);

        return response([
            'message' => 'Comment created successfully',
            'comment' => $comment
        ], 200);
    }

    //update comment on a post
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        if ($comment->users_id != $request->user()->id) {
            return response([
                'message' => 'You are not owner of this comment'
            ], 401);
        }

        //validate fields
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $request->comment
        ]);


        return response([
            'message' => 'Comment updated successfully',
            'comment' => $comment
        ], 200);
    }

    //delete comment on a post
    public function destroy($id, $comment_id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $comment = $post->comments()->find($comment_id);
        if (!$comment) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        $comment->delete();

        return response([
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
