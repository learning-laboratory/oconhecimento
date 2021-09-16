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
    public function __construct()
    {
    }

    public function home()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(9);
        $recommendedArticles = Article::take(6)->get();
        $categories = Category::all();
        return view('blog.home', [
            'articles' => $articles,
            'recommendedArticles' => $recommendedArticles,
            'categories' => $categories,
            'title' => 'Artigos'
        ]);
    }


    public function about()
    {
        $categories = Category::all();
        return view('blog.about', [
            'categories' => $categories
        ]);
    }

    public function contact()
    {
        $categories = Category::all();
        return view('blog.contact', [
            'categories' => $categories
        ]);
    }

    public function articles()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return view('blog.articles', [
            'articles' => $articles,
            'categories' => $categories,
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

    public function archive($month)
    {
        $articles = Article::whereMonth('created_at', $month)->orderBy('created_at', 'desc')->paginate(8);
        $month =  ucfirst(Carbon::createFromDate(null, $month, null)->monthName);
        $categories = Category::all();

        return view('blog.articles', [
            'articles' => $articles,
            'categories' => $categories,
            'title' => 'Publicados no mês de ' . $month
        ]);
    }

    public function category($category_id)
    {
        $category = Category::findOrFail($category_id);
        $articles = $category->articles()->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return view('blog.articles', [
            'articles' => $articles,
            'categories' => $categories,
            'title' => 'Publicados na categoria de ' . $category->name
        ]);
    }

    public function search_category($category_id)
    {
        $category = Category::findOrFail($category_id);
        $articles = $this->composeArticlesForHomePageHtml($category->articles()->orderBy('created_at', 'desc')->get());
        $mostViewArticles = $this->composeArticlesForHomePageHtml($category->articles()->orderBy('views', 'desc')->take(6)->get());

        return response()->json([
            'articles' => $articles,
            'mostViewArticles' => $mostViewArticles
        ]);
    }

    private function composeArticlesForHomePageHtml($articles)
    {
        $articlesHtml = [];
        foreach ($articles as $article) {
            $image = $article->getFeaturedImage();
            $link = $article->getLink();
            $title = $article->title;
            $created_at = $article->getCreatedAtFormated();
            $updated_at = $article->getUpdatedAtFormated();

            $published = '<p class="card-text"><small class="text-muted">Última atualização ' . $updated_at . '</small></p>';
            if ($created_at == $updated_at) {
                $published =  '<p class="card-text"><small class="text-muted">Publicado' . $created_at . '</small></p>';
            }

            $articlesHtml[] = '<div class="col align-items-stretch">
                    <div class="card">
                        <img src="' . $image . '" class="card-img-top"
                            alt="Capa da Imagem">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="' . $link . '">
                                    <h4 class="card-title">' . $title . '</h4>
                                </a>
                            </h5>
                            ' . $published . '
                        </div>
                    </div>
                </div>';
        }
        return $articlesHtml;
    }

    public function search(Request $request)
    {
        $term = '%' . $request->term . '%';
        $articles = Article::where('title', 'like', $term)->get();
        $articles = $this->composeArticlesForCategoryPageHtml($articles);
        return response()->json($articles);
    }

    private function composeArticlesForCategoryPageHtml($articles)
    {
        $articlesHtml = [];
        foreach ($articles as $article) {
            $image = $article->getFeaturedImage();
            $link = $article->getLink();
            $title = $article->title;
            $author = $article->author->name;
            $created_at = $article->created_at->diffForHumans();

            $articlesHtml[] = '<div class="col py-3"><div class="card shadow-sm">
                <img src="' . $image . '" class="card-img-top" width="100%" height="235" alt="Capa do Artigo">
                    <div class="card-body">
                        <h2 class="article-title">
                            <a href="' . $link . '">'
                . $title .
                '</a>
                        </h2>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="article-author">
                                De ' . $author . '
                            </div>
                            <small class="text-muted article-published">Publicado
                            ' . $created_at . '
                            </small>
                        </div>
                    </div>
                </div>
            </div>';
        }
        return $articlesHtml;
    }
}
