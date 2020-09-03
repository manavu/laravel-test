<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Post extends Model
{
    use Sortable;   // ソート可能にするためのトレイト

    public $sortable = ['id', 'context', 'created_at'];    // ソート対象カラム追加

    protected $dates = ['created_at'];

    // 更新を行ってはいけない列
    protected $guarded = [
        'id'
    ];

    /**
     * アタッチメントを取得
     */
    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    /**
     * タグを取得
     */
    public function tags()
    {
        // 多対多の関連
        return $this->belongsToMany('App\Tag');
    }
}
