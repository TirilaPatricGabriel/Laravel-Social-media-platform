<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    // public function index()
    // {
    //     $comments = Comment::paginate(6);

    //     return response(['comments' => $comments]);
    // }


    public function store(Request $request, $post_id)
    {
        $fields = $request->validate([
            'text' => 'string|required'
        ]);

        $user_id = Auth::id();
        $comment = Comment::create([
            'text' => $fields['text'],
            'user_id' => $user_id,
            'post_id' => $post_id,
            'upvotes' => 0,
            'downvotes' => 0
        ]);

        return response(['comment' => $comment, 'success' => 'Comment created successfully!']); 
    }


    public function show($id)
    {
        $comment = Comment::findOrFail($id);

        return response(['comment' => $comment]);
    }

    
    public function update(Request $request, $comment_id)
    {
        $user_id = Auth::id();
        $comment = Comment::findOrFail($comment_id);

        if(!$user_id || $user_id != $comment->user_id){
            return response(['error' => 'This is not your comment!']);
        }

        $comment->update([
            'text' => $request->text,
            // 'upvotes' => $request->upvotes,
            // 'downvotes' => $request->downvotes
        ]);

        return response(['comment' => $comment, 'success' => 'Your comment has been updated successfully!']);
    }


    public function destroy($comment_id)
    {
        $user_id = Auth::id();
        $comment = Comment::findOrFail($comment_id);

        if(!$user_id || $user_id != $comment->user_id){
            return response(['error' => 'This is not your comment!']);
        }

        $comment->delete();
        return response(['success' => 'Comment deleted successfully!']);
    }
}
