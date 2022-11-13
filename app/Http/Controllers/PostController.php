<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Jobs\CreatePostJob;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(40);

        return response(['posts' => $posts]);
    }


    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'string|required',
            'text' => 'string|required'
        ]);
        $fields['user_id'] = Auth::id(); // sterge daca nu e corect

        $user_id = Auth::id();
        $post = Post::create([
            'title' => $request->title,
            'text' => $fields['text'],
            'user_id' => $user_id,
            'upvotes' => 0,
            'downvotes' => 0
        ]);

        return response(['post' => $post, 'success' => 'Post created successfully!']); 
        // CreatePostJob::dispatch($fields);
    }


    public function show($id)
    {
        $post = Post::findOrFail($id);
        $comments = $post->comments()->paginate(8);

        return response(['post' => $post, 'comments' => $comments]);
    }

    
    public function update(Request $request, $id)
    {
        $user_id = Auth::id();
        $post = Post::findOrFail($id);

        if(!$user_id || $user_id != $post->user_id){
            return response(['error' => 'This is not your post!']);
        }

        $post->update([
            'title' => $request->title,
            'text' => $request->text,
            // 'upvotes' => $request->upvotes,
            // 'downvotes' => $request->downvotes
        ]);

        return response(['post' => $post, 'success' => 'Your post has been updated successfully!']);
    }


    public function destroy($id)
    {
        $user_id = Auth::id();
        $post = Post::findOrFail($id);
        
        if(!$user_id || $user_id != $post->user_id){
            return response(['error' => 'This is not your post!']);
        }

        $post->delete();
        return response(['success' => 'Post deleted successfully!']);
    }
}
