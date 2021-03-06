<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    private $messages;

    public function __construct()
    {
        $this->messages = [
            'required' => ':attribute es un campo obligatorio.',
            'string' => ':attribute debe ser una cadena de texto.',
            'max' => ':attribute debe ser máximo de :max caracteres.',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $posts = Post::filterPosts()->orderBy('created_at', 'DESC')->paginate();

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
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'files' => ['nullable', 'array'],
            'published' => ['boolean'],
            'featured' => ['boolean']
        ], $this->messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors(),
            ]);
        }
        
        $create_post = Post::create([
            'title' => $req->title,
            'description' => $req->description,
            'published' => $req->published,
            'featured' => $req->featured,
        ]);

        $create_post->created_by()->associate(auth()->user()->id);
        $create_post->save();

        if($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('media')->put('/' . now()->year . '/'. now()->month, $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                    ]);
                $create_post->attachments()->save($attachment);
            }
        }

        return $create_post
            ? response()->json(['success' => true, 'msg' => 'Post creado correctamente'])
            : response()->json(['error' => true, 'msg' => 'El post no se ha podido crear correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json([
            'success' => true,
            'post' => $post->load(['attachments'])
        ]);
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
        $post->attachments()->delete();

        $post->update([
            'deleted_by' => auth()->user()->id
        ]);

        $post->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Post eliminado correctamente.'
        ]);
    }

    public function get_posts_counters() {
        $published = Post::where('published', 1)->count();
        $featured = Post::where('featured', 1)->count();

        return response()->json([
            'success' => true,
            'published' => $published,
            'featured' => $featured
        ]);
    }
    public function toggle_post(Post $post, Request $req) {

        switch ($req->toggle) {
            case 'featured':
                $post->update([
                    'featured' => !$post->featured
                ]);
                break;
            case 'published':
                $post->update([
                    'published' => !$post->published
                ]);
                break;
        }
        
        return response()->json([
            'success' => true
        ]);
    }

    public function edit_post(Post $post, Request $req) {

        $validator = Validator::make($req->all(), [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'files' => ['nullable', 'array'],
        ], $this->messages, $this->attributes);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors(),
            ]);
        }

        $post->update([
            'title' => $req->title,
            'description' => $req->description,
            'featured' => $req->featured == "true" ? 1 : 0,
        ]);

        $post->user()->associate(auth()->user());
        $post->save();

        if($req->file('files')) {
            foreach ($req->file('files') as $file) {
                // NO CREA LA IMAGEN
                $stored_file = Storage::disk('media')->put('/' . now()->year . '/'. now()->month, $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                    ]);
                $post->attachments()->save($attachment);
            }
        }

        return $post
            ? response()->json(['success' => true, 'msg' => 'Post editado correctamente'])
            : response()->json(['error' => true, 'msg' => 'El post no se ha podido editar correctamente']);
    }

    public function published_posts(Request $req)
    {
        $posts = Post::filterPosts()
            ->with(['attachments'])
            ->orderBy('featured', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->when(request()->input('limit'), function(Builder $q, int $limit) {
                return $q->limit($limit)->get();
            }, function(Builder $q){
                return $q->paginate();
            });

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function published_posts_mobile(Request $req) {
        $posts = Post::filterPosts()
            ->orderBy('featured', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->skip($req->offset)
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function get_other_posts(Post $post)
    {
        $posts = Post::where('id', '<>', $post->id)
            ->where('featured', false)->inRandomOrder()->limit(3)->get();

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }
}
