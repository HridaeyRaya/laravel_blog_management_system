<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'user_id',
        'view_count'
    ];

//    Relationships
    public function user(): BelongsTo
    {
    return $this->belongsTo(User::class);
    }
    public function comments(): HasMany
    {
    return $this->hasMany(Comment::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

//    Polymorphic relationships to statuses table
     public function status(): MorphOne
     {
         return $this->morphOne(Status::class, 'statusable');
     }

//    Scope: only published posts
      public function scopePublished($query)
      {
          return $query -> whereHas('status', function ($q) {
              $q -> where('value', 'published');
          });
      }

}
