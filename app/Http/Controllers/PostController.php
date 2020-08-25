<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        // DBよりpostsテーブルの値を全て取得
        $posts = Post::all();

        // 取得した値をビュー「post/index」に渡す
        return view('post/index', compact('posts'));
    }

    public function create()
    {
        return view('post/create');
    }

    public function store(Request $request)
    {
        $validateRules = [
            'context' => 'required|max:10',
        ];

        $validateMessages = [
            "required" => "必須項目です。",
            "context.max" => "10文字以内で入力してください。"
        ];

        // バリデーションに失敗した場合はリダイレクトされる
        // エラー内容はセッションに保持されるので、@error ディレクティブを使うことでレンダリング可能
        // form ファサードを使うと、old ヘルパーを使わないでも以前の値がレンダリングされるっぽい
        $this->validate($request, $validateRules, $validateMessages);

        // モデルからインスタンスを生成
        $post = new Post;

        // $requestにformからのデータが格納されているので、以下のようにそれぞれ代入する
        $post->context = $request->context;

        // 保存
        $post->save();

        // 保存後 一覧ページへリダイレクト
        return redirect('/post');
    }

    public function edit($id)
    {
        $post = Post::find($id);

        return view('post/edit', ['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        // バリデーションを共有するための方法を考える必要がある

        $post = Post::find($id);
        $post->context = $request->context;
        $post->save();

        // 詳細ページへリダイレクト
        return redirect('/post/' . $id);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        // 一覧にリダイレクト
        return redirect('/post');
    }
}
