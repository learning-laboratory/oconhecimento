<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


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
        $articles = $this->composeHtml($articles);
        return response()->json($articles);
    }

    private function composeHtml($articles){
        $html = '';
        $articlesHtml = [];
        foreach ($articles as $article){

            $img = $article->getFeturedImage();
            $route = route('blog.article', $article->id);
            $title = $article->title;
            $content = $article->content;

            $categories = '';
            if(count($article->categories) > 0){
                foreach ($article->categories as $category)
                    $categories .='<span class="badge badge-dark">'.$category->name.'</span>';
            }else{
                $categories .= 'Sem Categoria';
            }

            $created_at = $article->created_at->format('d-M-Y');
            $content = '';
            if($article->summary){
                $content = Str::limit($article->summary, 160, '...');
            }else{
                $content = Str::limit($article->content, 160, ' ...');
            }

            $html = '<div class="col-sm-12 col-md-4 px-3">
                        <img class="article-image rounded py-2" width="300" height="220" src="'.$img.'" alt="Capa do Artigo">
                        <h2 class="article-title py-2">
                            <a href="'.$route.'">
                                '.$title.'
                            </a>
                        </h2>
                        <div class="article-categories">'. $categories .'</div>
                        <div class="article-published">'.$created_at.'</div>
                        <div class="article-content py-3">'.$content.'</div>
                    </div>';

            $articlesHtml[] = $html;
        }
        return $articlesHtml;
    }
}
