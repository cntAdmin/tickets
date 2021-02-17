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
    private $attributes;
    private $messages;

    public function __construct()
    {
        $this->attributes = [
            'title' => __('Título'),
            'description' => __('Descripción'),
            'likes' => __('Me gusta'),
            'dislikes' => __('No me gusta'),
            'featured' => __('Destacado'),
        ];

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
            $posts = Post::when($req->text, function(Builder $q, $text) {
                $q->where('title', 'LIKE', '%' . $text. '%')->orWhere('description', 'LIKE', '%' . $text . '%');
            })->when($req->date_from, function(Builder $q, $date_from) {
                $q->whereDate('created_at', '>=', $date_from);
            })->when($req->date_to, function(Builder $q, $date_to) {
                $q->whereDate('created_at',  '<=', $date_to);
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
    public function store(Request $req)
    {
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

        $create_post = Post::create([
            'title' => $req->title,
            'description' => $req->description,
            'featured' => $req->featured == "true" ? 1 : 0,
        ]);

        $create_post->user()->associate(auth()->user());
        $create_post->save();

        if($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('media')->put('/', $file);
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
            'post' => $post
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
                $stored_file = Storage::disk('media')->put('/', $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                    ]);
                $post->attachments()->save($attachment);
            }
        }

        return $post
            ? response()->json(['success' => true, 'msg' => 'Post editado correctamente'])
            : response()->json(['error' => true, 'msg' => 'El post no se ha podido editar correctamente']);    }
}
