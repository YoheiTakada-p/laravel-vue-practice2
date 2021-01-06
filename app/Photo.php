<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //intにキャストさせない
    public $incrementing = false;

    //Json
    protected $appends = [
        'url'
    ];
    //JSONに含める属性
    protected $visible = [
        'id', 'owner', 'url',
    ];
    /**
     * 12桁のランダムな文字列をIDとして設定する
     */

    //オーバーライド
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!\Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    //ランダムなID値をid属性に代入する
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    //ランダムなID値を生成する
    private function getRandomId()
    {
        return \Str::random(12);
        // return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 12);
    }

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
        return \Storage::cloud()->url($this->attributes['filename']);
    }

    /**
     * リレーションシップ - usersテーブル
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
