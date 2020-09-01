<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * list()を呼んだらscopeList()が呼ばれる
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public static function scopeList($query)
    {
        // select タグ用のリスト表示の結果だけを返す
        return $query->select(['id', 'name']);
    }

    /**
     * 記事を取得
     */
    public function posts()
    {
        // 多対多の関連
        return $this->belongsToMany('App\Post');
    }
}
