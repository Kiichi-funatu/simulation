<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    // 商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）
    /** @test */
    public function user_can_create_item_with_required_fields()
    {
        Storage::fake('public');

        Condition::factory()->create(['id' => 2]);
        Category::factory()->create(['id' => 1]);
        Category::factory()->create(['id' => 3]);
        
        // 1. ログインユーザー作成
        $user = User::factory()->create();

        // 2. 出品ページへアクセス
        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        // 3. 入力データ
        $formData = [
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => '1,3',
            'condition_id' =>2,
            'name' => 'TEST_ITEM_NAME',
            'brand' => 'TEST_BRAND',
            'description' => 'TEST_DESCRIPTION',
            'price' => 9999,
        ];

        // 4. post 送信
        $response = $this->actingAs($user)->post('/sell', $formData);

        

        // 5. 保存されたか確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => 2,
            'name' => 'TEST_ITEM_NAME',
            'brand' => 'TEST_BRAND',
            'description' => 'TEST_DESCRIPTION',
            'price' => 9999,
        ]);

        // item_id を取得
        $item = Item::where('name', 'TEST_ITEM_NAME')->first();

        // 中間テーブルに保存されていること
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => 1,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => 3,
        ]);

        // 画像が保存されたこと
        $storedPath = str_replace('/storage/', '', $item->images->first()->image_path);
        Storage::disk('public')->assertExists($storedPath);
        //Storage::disk('public')->assertExists('items/' . $item->images->first()->image_path);
    }
}
