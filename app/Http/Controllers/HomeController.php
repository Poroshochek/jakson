<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(3);

        return view('pages.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail(); //firstOrFail() return 404

        return view('pages.show', compact('post'));
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()->paginate(4);

        return view('pages.list', ['posts' => $posts]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = $category->posts()->paginate(4);

        return view('pages.list', ['posts' => $posts]);
    }
}
