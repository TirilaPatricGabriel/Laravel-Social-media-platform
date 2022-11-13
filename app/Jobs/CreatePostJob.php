<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;

class CreatePostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $fields;
    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $post = Post::create([
            'title' => $this->fields['title'],
            'text' => $this->fields['text'],
            'user_id' => $this->fields['user_id'],
            'upvotes' => 0,
            'downvotes' => 0
        ]);

        return response(['post' => $post, 'success' => 'Post created successfully!']); 
    }
}
