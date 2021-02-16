<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $posts = Post::when($req->text, function(Builder $q, $text) {
                $q->where('title', 'LIKE', '%' . $text. '%')->orWhere('description', 'LIKE', '%' . $text . '%');
            })->when($req->date_from, function(Builder $q, $date_from) {
                $q->whereDate('created_at', $date_from);
            })->when($req->date_to, function(Builder $q, $date_to) {
                $q->whereDate('created_at', $date_to);
            })->with(['user'])
            ->paginate();

            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function get_posts_counters() {
        $published = Post::where('featured', 1)->count();

        return response()->json([
            'success' => true,
            'published' => $published
        ]);
    }
    public function toggle_featured_post(Post $post) {
        $post->update([
            'featured' => !$post->featured
        ]);
        
        return response()->json([
            'success' => true
        ]);
    }
}
