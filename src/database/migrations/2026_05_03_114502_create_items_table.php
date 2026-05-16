<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
           $table->id(); // 商品ID

            // FK
           $table->unsignedBigInteger('user_id');       // 出品者
           $table->unsignedBigInteger('condition_id');  // 商品状態

           // 商品情報
           $table->string('name', 255);       // 商品名
           $table->string('brand', 255)->nullable(); // ブランド名
           $table->text('description');       // 商品説明（長文）
           $table->integer('price');          // 金額（税込）

           // タイムスタンプ
           $table->timestamps();

           // 外部キー制約
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           
           $table->foreign('condition_id')->references('id')->on('conditions')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
