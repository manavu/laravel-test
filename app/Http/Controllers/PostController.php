<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use App\Services\IPostService;

class PostController extends Controller
{
    // private $postService = null;

    public function __construct()
    // public function __construct(IPostService $postService)
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
        $tags = Tag::list()->pluck('name', 'id');

        return view('post/create', compact('tags'));
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
        // バリデーターにリクエスト内容とルールを入れる
        $validation = Validator::make($request, $rules);

        // バリデーションチェックを行う
        if ($validation->fails()) {
            return redirect('/')->with('message', 'ファイルを確認してください！');
        }
 */
        $service = app()->make(IPostService::class);
        $service->create($request);

        // 保存後 一覧ページへリダイレクト
        return redirect('/post');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        $tags = Tag::list()->pluck('name', 'id');

        return view('post/edit', ['post' => $post, 'tags' => $tags]);
    }

    public function update(Request $request, $id)
    {
        $service = app()->make(IPostService::class);
        $service->update($request, (int)$id);

        // 詳細ページへリダイレクト
        return redirect('/post/' . $id);
    }

    public function destroy($id)
    {
        $service = app()->make(IPostService::class);
        $service->delete((int)$id);

        // 一覧にリダイレクト
        return redirect('/post');
    }
}
