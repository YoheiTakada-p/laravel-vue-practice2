<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * 12桁のランダムな文字列をIDとして設定する
     */

    //オーバーライド
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!Arr::get($this->attributes, 'id')) {
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
        return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 12);
    }
}
