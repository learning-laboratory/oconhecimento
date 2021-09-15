<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    function __construct()
    {
    }

    public function home()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        return view('blog.home', [
            'articles' => $articles,
            'title' => 'Artigos',
        ]);
    }

    public function articles()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(8);
        return view('blog.articles', [
            'articles' => $articles,
            'title' => 'Artigos',
        ]);
    }

    public function article($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all();
        $articles = Article::take(5)->orderBy('created_at', 'desc')->get();
        $archives = Article::selectRaw('month(created_at) month, year(created_at) year, count(*) num_articles')
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->get();

        return view('blog.article', [
            'article' => $article,
            'articles' => $articles,
            'archives' => $archives,
            'categories' => $categories
        ]);
    }

    public function category($category_id)
    {
        $category = Category::findOrFail($category_id);
        $articles = $category->articles()->orderBy('created_at', 'desc')->paginate(8);

        return view('blog.articles', [
            'articles' => $articles,
            'title'    => 'Artigos da Categoria de ' . $category->name
        ]);
    }

    public function archive($month)
    {
        $articles = Article::whereMonth('created_at', $month)->orderBy('created_at', 'desc')->paginate(8);
        $month =  ucfirst(Carbon::createFromDate(null, $month,null)->monthName);

        return view('blog.articles', [
            'articles' => $articles,
            'title' => 'Artigos do Mes de ' .$month
        ]);
    }

    public function contact()
    {
        return view('blog.contact');
    }

    public function search(Request $request)
    {
        $term = '%' . $request->term . '%';
        $articles = Article::where('title', 'like', $term)->get();
        $articles = $this->composeHtml($articles);
        return response()->json($articles);
    }

    private function composeHtml($articles)
    {
        $articlesHtml = [];
        foreach ($articles as $article) {

            $image = $article->getFeaturedImage();
            $link = $article->getLink();
            $title = $article->title;
            $author = $article->author->name;
            $created_at = $article->created_at->diffForHumans();

            $articlesHtml[] = '<div class="col py-3"><div class="card shadow-sm">
                <img src="'.$image.'" class="card-img-top" width="100%" height="235" alt="Capa do Artigo">
                    <div class="card-body">
                        <h2 class="article-title">
                            <a href="'. $link .'">'
                            .$title.
                            '</a>
                        </h2>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="article-author">
                                De '.$author.'
                            </div>
                            <small class="text-muted article-published">Publicado
                            '.$created_at.'
                            </small>
                        </div>
                    </div>
                </div>
            </div>';
        }
        return $articlesHtml;
    }
}
