<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kyslik\ColumnSortable\Sortable;
use Reshadman\OptimisticLocking\OptimisticLocking;
use App\Traits\CamelCaseAccessible;

class Post extends Model
{
    use CamelCaseAccessible;
    use OptimisticLocking;  // 同時実行制御を可能にするためのトレイト
    use Sortable;   // ソート可能にするためのトレイト

    public $sortable = ['id', 'context', 'created_at'];    // ソート対象カラム追加

    protected $dates = ['created_at'];

    // 更新を行ってはいけない列
    protected $guarded = [
        'id'
    ];

    /**
     * コンストラクタ
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // モデルのコンストラクタのルール
        parent::__construct($attributes);

        // 必須プロパティなので初期化
        $this->lockVersion = 0;
    }

    /**
     * アタッチメントを取得
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * タグを取得
     */
    public function tags(): BelongsToMany
    {
        // 多対多の関連
        return $this->belongsToMany(Tag::class);
    }

    public function casts(): BelongsToMany
    {
        return $this->belongsToMany(Cast::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'post_tag', 'post_id', 'tag_id');
    }
}
