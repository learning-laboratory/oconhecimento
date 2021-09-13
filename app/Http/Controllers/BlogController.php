<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        return view('blog.index', [
            'articles' => $articles
        ]);
    }

    public function article($id)
    {
        $article = Article::findOrFail($id);
        return view('blog.article', [
            'article' => $article
        ]);
    }

    public function search(Request $request)
    {
        $term = '%'.$request->term.'%';
        $articles = Article::where('title','like',$term)->get();
        return response()->json($articles);
    }
}
