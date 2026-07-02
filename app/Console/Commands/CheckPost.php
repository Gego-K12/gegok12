<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Log;

class CheckPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkpost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Post';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try
        {
            $now = date('Y-m-d H:i:s');
            $posts = Post::where('is_posted',0)->where('post_created_at','<=',$now)->get();

            foreach($posts as $post)
            {
                $is_posted['is_posted'] = 1;

                Post::where('id',$post->id)->update($is_posted);

                $posted_at['status'] = "posted";
                $posted_at['posted_at'] = date('Y-m-d H:i:s');

                Post::where('id',$post->id)->update($posted_at);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}