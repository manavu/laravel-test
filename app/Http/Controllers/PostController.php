<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use App\Services\IPostService;
use App\Http\Requests\StorePost;

use Illuminate\Support\Facades\Auth;

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

    public function index(Request $request)
    {
        $keyword = $request->input('keyword', session('keyword'));
        session(['keyword' => $keyword]);

        $posts = Post::query();

        // 引数があれば検索条件を追加していく
        if (!empty($keyword)) {
            // 空白区切りの単語配列に変換する
            $keywords = explode(' ', str_replace(['　'], ' ', $keyword));

            // or で検索条件を追加する
            $posts = $posts->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query = $query->orWhere('context', 'like', '%' . $word . '%');
                    // 関連先の tag の name で条件を追加する
                    $query = $query->orWhereHas('tags', function ($query) use ($word) {
                        $query = $query->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
        }

        // DBよりpostsテーブルの値をページネーションの形式で取得
        $posts = $posts->paginate(15);    // 一ページ辺り15件

        // こんな感じでも実装は可能だと思うが素直に使ったほうがよいだろう
        // $posts = Post::skip($request->page * 15)->take(15)->get();

        // 取得した値をビュー「post/index」に渡す
        return view('post/index', ['posts' => $posts, 'keyword' => $keyword]);
        // return view('post/index', compact('posts'));
    }

    public function create()
    {
        $tags = Tag::list()->pluck('name', 'id');

        return view('post/create', compact('tags'));
    }

    public function store(StorePost $request)
    // public function store(Request $request)
    {
        // フォームリクエストを作るとメソッドが呼び出される前にバリデーションルールが適応される
        // バリデーションに失敗した場合はリダイレクトされる
        // エラー内容はセッションに保持されるので、@error ディレクティブを使うことでレンダリング可能
        // form ファサードを使うと、old ヘルパーを使わないでも以前の値がレンダリングされるっぽい

        /*
        // 簡単に使用する場合はこの書き方でもOK
        $validateRules = [
            'context' => 'required|max:10',
        ];

        $validateMessages = [
            "required" => "必須項目です。",
            "context.max" => "10文字以内で入力してください。"
        ];

        $this->validate($request, $validateRules, $validateMessages);
        */

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

    public function update(StorePost $request, $id)
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
