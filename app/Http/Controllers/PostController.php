<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\Attachment;

class PostController extends Controller
{
    public function __construct()
    {
        // 認証ありのコントローラー
        $this->middleware('auth');
        // 認証なしのコントローラーだが、特定のアクションは除外
        // $this->middleware('guest')->except('create');
    }

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

        /*      
        // こういうやり方もあるっぽい
        // バリデーターにルールとインプットを入れる
        $validation = Validator::make($request, $rules);

        // バリデーションチェックを行う
        if ($validation->fails()) {
            return redirect('/')->with('message', 'ファイルを確認してください！');
        }
 */
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
            }

            // transaction メソッドの戻り値として返すことができる
            // return $value;
        });

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
        DB::transaction(function () use ($id) {
            $post = Post::find($id);

            // EF みたいに勝手にやってくれないので、モデル側のトリガーで定義するか、 DB でカスケードの設定をするとよいかも
            foreach ($post->attachments()->get() as $attachment) {
                // foreach ($post->attachments() as $attachment) {  // こっちだとダメだった。なぜ？
                $attachment->delete();
            }

            $post->delete();
        });

        // 一覧にリダイレクト
        return redirect('/post');
    }
}
