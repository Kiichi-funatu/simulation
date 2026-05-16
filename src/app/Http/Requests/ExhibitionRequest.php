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
            'image'        => 'required|image|mimes:jpeg,png|max:2048',
            'category_ids'  => 'required|string',
            'condition_id' => 'required|integer|exists:conditions,id',
            'name'         => 'required|string|max:255',
            'brand'        => 'nullable|string|max:255',
            'description'  => 'required|string|max:255',
            'price'        => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'image.required'       => '商品画像は必須です。',
        'image.image'          => '画像ファイルを選択してください。',
        'image.mimes'          => '画像はjpegまたはpng形式のみアップロードできます。',

        'category_ids.required' => 'カテゴリーを選択してください。',
        

        'condition_id.required' => '商品の状態を選択してください。',
        'condition_id.integer'  => '状態IDが不正です。',
        'condition_id.exists'   => '選択した状態は存在しません。',

        'name.required'        => '商品名は入力必須です。',
        'name.max'             => '商品名は255文字以内で入力してください。',

        'description.required' => '商品説明は入力必須です。',
        'description.max'      => '商品説明は255文字以内で入力してください。',

        'price.required'       => '価格は入力必須です。',
        'price.integer'        => '価格は数値で入力してください。',
        'price.min'            => '価格は0円以上で入力してください。',
        ];
    }
}
