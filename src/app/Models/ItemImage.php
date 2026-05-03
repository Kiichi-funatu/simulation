<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'image_path', // storage に保存した画像パス
    ];

    //商品（逆リレーション）
    public function item() {
        return $this->belongsTo(Item::class);
    }
}
