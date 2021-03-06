<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Tag $tag = null)
    {
        // return $tag->posts;
        // return session('message');

        $posts = Post::latest()
            ->filter(request(['month', 'year']))
            ->get();


        // $archives = Post::selectRaw('year(created_at) year, monthname(created_at) month, count(*) published')
        //             ->groupBy('year', 'month')
        //             ->orderByRaw('min(created_at) desc')
        //             ->get()
        //             ->toArray();

        // $archives = Post::archives();

    	return view('posts.index', compact('posts', 'archives'));
    }

    /**
     * Show a post
     */
    public function show(Post $post)
    {
    	return view('posts.show', compact('post'));
    }

    public function create()
    {
    	return view('posts.create');
    }

    public function store()
    {
        $this->validate(request(), [
            'title' => 'required',
            'body' => 'required'
        ]);

        auth()->user()->publish(
            new Post(request(['title', 'body']))
        );

        session()->flash('message', 'Your post has been created!');

        return redirect('/');
    }
}
