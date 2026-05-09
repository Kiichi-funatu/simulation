<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    //　保存可能なカラム
    protected $fillable = [
        'name',
        'price',
        'description',
        'user_id',
    ];

    // 商品画像（1対1）
    /* public function image() {
        return $this->hasOne(ItemImage::class);
    } */

        public function images()
        {
            return $this->hasMany(ItemImage::class);
        }

        public function condition()
        {
            return $this->belongsTo(Condition::class);
        }

        public function category()
        {
            return $this->belongsTo(Category::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function favorites()
        {
            return $this->hasMany(Favorite::class);
        }

        public function comments()
        {
            return $this->hasMany(Comment::class);
        }

        public function isFavoritedBy($user)
        {
            return $this->favorites->where('user_id', $user->id)->count() > 0;
        }
}
