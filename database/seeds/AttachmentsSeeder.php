<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AttachmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!is_dir(storage_path() . '/app/public/media')){
            File::makeDirectory(storage_path(). '/app/public/media', 0777, true, true);
        }
    
        factory(App\Models\Attachment::class, 50)->create()
            ->each(function($attachment){
                $attachment->comments()->save(App\Models\Comment::find(rand(1,50)));
            });
    }
}
