<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_images', function (Blueprint $table) {
            $table->id(); // 画像ID

            // 外部キー
            $table->unsignedBigInteger('item_id'); // 商品ID

            // 画像パス
            $table->string('image_path', 255); // 画像ファイルパス

            // タイムスタンプ
            $table->timestamps();

            // 外部キー制約
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade'); // 商品削除時に画像も削除
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_images');
    }
}
