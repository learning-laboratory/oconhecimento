<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'sumary',
        'user_id',
        'photo_id',
    ];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function categories()
    {
       return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
       return $this->belongsToMany(Tag::class);
    }

    public function isMyCategory($category_id)
    {
        return in_array($category_id, $this->categories->pluck('id')->toArray());
    }

    public function getFeturedImage()
    {
        return $this->photo ? asset('storage/'.$this->photo->path) : 'https://images.unsplash.com/photo-1603349206295-dde20617cb6a?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80';
    }
}
