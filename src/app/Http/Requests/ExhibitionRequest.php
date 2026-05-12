<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|string|max:255',     // 商品名：入力必須
            'description' => 'required|string|max:255',     // 商品説明：入力必須、最大255文字
            'image'       => 'required|image|mimes:jpeg,png|max:2048', // 商品画像：必須、jpeg/png
            'category'    => 'required|string',             // カテゴリー：選択必須
            'condition'   => 'required|string',             // 商品の状態：選択必須
            'price'       => 'required|integer|min:0',      // 価格：必須、数値、0円以上
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => '商品名は入力必須です。',
            'description.required' => '商品説明は入力必須です。',
            'description.max'      => '商品説明は255文字以内で入力してください。',
            'image.required'       => '商品画像は必須です。',
            'image.image'          => '画像ファイルを選択してください。',
            'image.mimes'          => '画像はjpegまたはpng形式のみアップロードできます。',
            'category.required'    => 'カテゴリーを選択してください。',
            'condition.required'   => '商品の状態を選択してください。',
            'price.required'       => '価格は入力必須です。',
            'price.integer'        => '価格は数値で入力してください。',
            'price.min'            => '価格は0円以上で入力してください。',
        ];
    }
}
