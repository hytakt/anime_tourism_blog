<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'body',
        'like_count',
        'user_id',
    ];
    
    public function bookmark()
    {
        return $this->belongsTo(Bookmark::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    public function animes()
    {
        return $this->belongsToMany(Anime::class);
    }
    
    public function prefectures()
    {
        return $this->belongsToMany(Prefecture::class);
    }
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    
    public function isLikedBy($user): bool {
        return Like::where('user_id', $user->id)->where('post_id', $this->id)->first() !==null;
    }
    
    
    public function getPaginateByLimit(int $limit_count = 10)
    {
        return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
    
    public function getPrefNameAttribute()
    {
        return config('pref.' . $this->pref_id);
    }

    public function searchFilter($query, string $search = null)
    {
        if (!$search) {
            return $query;
        }

        return $query->where('title', 'LIKE', "%{$search}%")
        ->orWhere('body', 'LIKE', "%{$search}%");
    }

    public function prefFilter($query, string $pref = null)
    {
        if (!$pref) {
            return $query;
        }
        
        

        return $query->Prefecture::where('id', $pref);
    }
}
