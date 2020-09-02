<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\Attachment;

class PostService implements IPostService
{
    public function create(Request $request)
    {
        DB::transaction(function () use ($request) {
            // モデルからインスタンスを生成
            $post = new Post;

            // $requestにformからのデータが格納されているので、以下のようにそれぞれ代入する
            $post->context = $request->context;

            // 保存
            $post->save();

            foreach ($request->files as $file) {
                $attachment = new Attachment();
                $attachment->data = $file;
                $attachment->name = $file->getClientOriginalName();
                $attachment->contentType = $file->getClientMimeType();  // mimeType は直接アクセスできない
                $attachment->size = $file->getClientSize();
                $attachment->postId = $post->id;

                $attachment->save();
                // $post->attachments()->save($attachment);
                // $post->attachments()->saveMany([$attachment]);
            }

            $tagIds = array_filter($request->tags, function ($tagId) {
                return !is_null($tagId);
            });

            // タグの関連を結ぶ。要素にないものは削除される
            $post->tags()->sync($tagIds);

            // transaction メソッドの戻り値として返すことができる
            // return $value;
        });
    }

    public function update(Request $request, int $id)
    {
        DB::transaction(function () use ($request, $id) {
            $post = Post::find($id);
            $post->context = $request->context;
            $post->save();

            $tagIds = array_filter($request->tags, function ($tagId) {
                return !is_null($tagId);
            });

            // タグの関連を結ぶ。要素にないものは削除される
            $post->tags()->sync($tagIds);
            // 要素にないものは削除されないで残る
            // $post->tags()->syncWithoutDetaching($tagIds);
        });
    }

    public function delete(int $id)
    {
        DB::transaction(function () use ($id) {
            $post = Post::find($id);

            // EF みたいに勝手にやってくれないので、モデル側のトリガーで定義するか、 DB でカスケードの設定をするとよいかも
            foreach ($post->attachments()->get() as $attachment) {
                // foreach ($post->attachments() as $attachment) {  // こっちだとダメだった。なぜ？
                $attachment->delete();
            }

            // 引数なしだとすべての多対多の関連が解除される
            $post->tags()->detach();

            $post->delete();
        });
    }
}
