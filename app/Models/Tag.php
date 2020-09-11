<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /**
     * list()を呼んだらscopeList()が呼ばれる
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public static function scopeList(Builder $query): Builder
    {
        // select タグ用のリスト表示の結果だけを返す
        return $query->select(['id', 'name']);
    }

    /**
     * 記事を取得
     */
    public function posts(): BelongsToMany
    {
        // 多対多の関連
        return $this->belongsToMany(Post::class);
    }
}
