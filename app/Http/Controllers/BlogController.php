<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index');
    }

    public function article($id)
    {
        $article = Article::findOrFail($id);
        return view('blog.article', [
            'article' => $article
        ]);
    }
}
