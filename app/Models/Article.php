<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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


    public function getFeaturedImage()
    {
        if ($this->photo)
            return asset('storage/' . $this->photo->path);
        return 'https://images.unsplash.com/photo-1603349206295-dde20617cb6a?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80';
    }

    public function getArchiveDate()
    {
        return Carbon::createFromDate($this->year, $this->month, null);
    }

    public function getLink()
    {
        return route('blog.article', $this->id);
    }

    public function getCreatedAtFormated()
    {
        return $this->created_at->diffForHumans();
    }

    public function getUpdatedAtFormated()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getCreatedAt()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

}
