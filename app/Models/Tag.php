<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Tag extends Model
{
    // 一つのテーブルを使って継承を表現するためのトレイト
    use SingleTableInheritanceTrait;

    // サブクラスで save メソッドを呼び出すのでテーブル名を明示
    protected $table = 'tags';

    // サブクラスの判断に使用する識別子が入っている列名
    protected static $singleTableTypeField = 'type';

    // 識別子からインスタンスを生成するクラス
    protected static $singleTableSubclasses = [Cast::class, Genre::class];

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

class Cast extends Tag
{
    // 継承元のテーブルの type 列に入る識別子
    protected static $singleTableType = 'cast';

    public function posts(): BelongsToMany
    {
        // 多対多の関連テーブルを明示しないと動作しなかったのでオーバーライドする
        return $this->belongsToMany(Post::class, 'post_tag', 'tag_id', 'post_id');
    }
}

class Genre extends Tag
{
    protected static $singleTableType = 'genre';

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'tag_id', 'post_id');
    }
}
