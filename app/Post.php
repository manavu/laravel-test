<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
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
