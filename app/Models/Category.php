<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function articles()
    {
      return $this->belongsToMany(Article::class);
    }

    public function getLink()
    {
        return route('blog.category', $this->id);
    }

    public function getSearchCategoryLink()
    {
        return route('blog.search_category', $this->id);
    }

}
