<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        // 認証ありのコントローラー
        $this->middleware('auth');

        /*
        // アクションが呼ばれる前のフィルター。5.0以降はミドルウェアを使うのが正しい
        $this->beforeFilter(function () {
            return true;
            // ucfirst('aaa');
        });*/
    }

    public function index(Request $request)
    {
        $controllerName = explode('/', $request->route()->uri)[0];
        $modelName = ucfirst($controllerName);

        $keyword = $request->input('keyword', session('keyword'));
        session(['keyword' => $keyword]);

        $casts = app('App\Models\\' . $modelName)->query();
        // $casts = Cast::query();

        // 引数があれば検索条件を追加していく
        if (!empty($keyword)) {
            // 空白区切りの単語配列に変換する
            $keywords = explode(' ', str_replace(['　'], ' ', $keyword));

            // or で検索条件を追加する
            $casts = $casts->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query = $query->orWhere('name', 'like', '%' . $word . '%');
                }
            });
        }

        // 一ページ辺り15件
        $casts = $casts->paginate(15);

        return view('tag/index', ['casts' => $casts, 'keyword' => $keyword, 'title' => $modelName, 'routeName' => $controllerName]);
    }

    public function create(Request $request)
    {
        $controllerName = explode('/', $request->route()->uri)[0];
        $modelName = ucfirst($controllerName);

        return view('tag/create', ['title' => $modelName, 'routeName' => $controllerName]);
    }

    public function store(Request $request)
    {
        $controllerName = explode('/', $request->route()->uri)[0];
        $modelName = ucfirst($controllerName);

        $cast = app('App\Models\\' . $modelName)->newInstance();
        // $cast = new Cast();
        $cast->name = $request->name;
        $cast->save();

        session()->flash('flashMessage', '登録が完了しました');

        // 保存後 一覧ページへリダイレクト
        return redirect()->route($controllerName . '.index');
    }

    public function edit(Request $request, $id)
    {
        $controllerName = explode('/', $request->route()->uri)[0];
        $modelName = ucfirst($controllerName);

        $cast = app('App\Models\\' . $modelName)->find($id)->first();

        return view('tag/edit', ['cast' => $cast, 'title' => $modelName, 'routeName' => $controllerName]);
    }

    public function update(Request $request, $id)
    {
        $controllerName = explode('/', $request->route()->uri)[0];
        $modelName = ucfirst($controllerName);

        $cast = app('App\Models\\' . $modelName)->find($id);
        $cast->name = $request->name;
        $cast->save();

        session()->flash('flashMessage', '更新が完了しました');

        // 詳細ページへリダイレクト
        return redirect()->route($controllerName . '.edit', [$id]);
    }

    public function destroy(Request $request, $id)
    {
        $controllerName = explode('/', $request->route()->uri)[0];
        $modelName = ucfirst($controllerName);

        $cast = app('App\Models\\' . $modelName)->find($id);

        // 関連が結ばれていると消せないので最初に関連を切る。カスケードの設定でよいかも
        foreach ($cast->posts()->get() as $post) {
            $post->tags()->detach($id);
        }
        $cast->delete();

        // 一覧にリダイレクト
        return redirect()->route($controllerName . '.index');
    }
}
