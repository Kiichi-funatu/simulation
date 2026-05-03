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
    public function image() {
        return $this->hasOne(ItemImage::class);
    }
}
