<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        return view('dashboard.articles.index', [
            'articles' => $articles
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('created_at', 'desc')->get()->pluck('name', 'id');
        $tags = Tag::orderBy('created_at', 'desc')->get()->pluck('name', 'id');
        return view('dashboard.articles.create', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function store(ArticleRequest $request)
    {
        $articleData = [
            'title'    => $request->title,
            'content'  => $request->content,
            'summary'  => $request->summary,
            'photo_id' => $this->uploadPhotoAndReturnPhotoId($request),
            'user_id'  => auth()->user()->id
        ];

        $article = Article::create($articleData);
        $article->categories()->sync($request->category_id);
        $article->tags()->sync($request->tag_id);

        return redirect()->route('articles.index')->with([
            'message' => 'Artigo registado com sucesso.'
        ]);
    }

    public function uploadPhotoAndReturnPhotoId(Request $request, Article $article = null): null|int
    {
        if (!$request->photo)
            return null;

        $path = $request->file('photo')->store('articles', 'public');

        if ($article && $article->photo) {
            $article->photo->update(['path' => $path]);
            return $article->photo->id;
        }

        $photo = Photo::create(['path' => $path]);
        return $photo->id;
    }

    public function edit(Article $article)
    {
        $categories = Category::orderBy('created_at', 'desc')->get()->pluck('name', 'id');
        $tags = Tag::orderBy('created_at', 'desc')->get()->pluck('name', 'id');

        $tags_selected       = $article->tags()->pluck('tags.id');
        $categories_selected = $article->categories()->pluck('categories.id');

        return view('dashboard.articles.edit', [
            'article' => $article,
            'categories' => $categories,
            'tags' => $tags,
            'tags_selected' => $tags_selected,
            'categories_selected' => $categories_selected
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $articleData = [
            'title'    => $request->title,
            'content'  => $request->content,
            'summary'  => $request->summary,
            'photo_id' => $this->uploadPhotoAndReturnPhotoId($request, $article),
            'user_id'  => auth()->user()->id
        ];

        $article->update($articleData);
        $article->categories()->sync($request->category_id);
        $article->tags()->sync($request->tag_id);

        return redirect()->route('articles.index')->with([
            'message' => 'Artigo actualizado com sucesso.'
        ]);
    }

    public function destroy(Article $article)
    {
        if ($article->photo) {
            unlink('storage/' . $article->photo->path);
            Photo::findOrFail($article->photo->id)->delete();
        }

        $article->categories()->sync([]);
        $article->tags()->sync([]);
        $article->delete();
        return redirect()->route('articles.index')->with([
            'message' => 'Artigo exluído com sucesso.'
        ]);
    }
}
