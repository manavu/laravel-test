<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagApiController extends Controller
{
    /**
     * タグの一覧を返す
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelName = ucfirst($request->type);
        $tags = app('App\Models\\' . $modelName)->all();

        // api のルーティングのコントローラーなので自動で json に変換される
        // 連想配列も同じように変換される
        return $tags;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag();
        $tag->name = $request->tagName;
        $tag->save();

        // このように json のデータを作って返す方法もある
        return response()->json($tag, 201, [], JSON_UNESCAPED_UNICODE);
        // return response()->json(['message' => 'created ok!'], 201, [], JSON_UNESCAPED_UNICODE);
    }
}
