<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;    // uuid を使用するので

class Attachment extends Model
{
    // id 列が UUID なので自動インクリメントを無効にする
    public $incrementing = false;

    // UUID の型は文字列なので
    protected $keyType = 'string';

    /**
     * アクセサ
     *
     * @return string
     */
    public function getContentTypeAttribute(): string
    {
        // これがないと、model->contentType = 'image/jpg'; と書くことができないっぽい
        return  $this->attributes['content_type'];
    }

    public function getDataAttribute(): string
    {
        return  base64_decode($this->attributes['data']);
    }

    /**
     * ミューテタ
     *
     * @param [type] $value
     * @return void
     */
    public function setPostIdAttribute($value)
    {
        $this->attributes['post_id'] = $value;
    }

    public function setContentTypeAttribute($value)
    {
        $this->attributes['content_type'] = $value;
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = base64_encode(file_get_contents($value));
        // $this->attributes['data'] = unpack('C*', file_get_contents($file)); // わざわざバイトの配列にしない？
    }

    /**
     * このアタッチメントを所有するポストを取得
     */
    public function post(): BelongsTo
    {
        // post_id が foreign_key となる。変えたい場合は、第二引数に列名を指定する
        return $this->belongsTo(Post::class);
    }

    protected static function boot()
    {
        parent::boot();

        // インサート時に呼ばれるトリガーを定義
        static::creating(function ($model) {
            // 主キーが UUID なのでこのような形で代入を行う。自動で定義する必要がない場合もあるかも
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}
