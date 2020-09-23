@extends('layouts/app')
@section('content')

{{-- 第一引数にバリデーションルールが入ったリクエストクラス、第二引数に jQuery のセレクターを渡すことができる --}}
{!! JsValidator::formRequest('App\Http\Requests\StorePost') !!}

<div class="container ops-main">
    <div class="row">
        <div class="col-md-12">
            <h3 class="ops-title">Post Edit</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ Html::link('/post', '一覧へ') }}
        </div>
    </div>
    {{ Form::open(['action' => ['PostController@update', $post->id], 'method' => 'put']) }}
    {{ Form::hidden('lockVersion', $post->lockVersion) }}
    <div class="form-group form-row">
        {{ Form::label('context', '内容:') }}
        {{ Form::text('context', $post->context, ['class' => 'form-control', 'placeholder' => ''] ) }}
        @error('context')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @foreach ($post->attachments as $attachment)
    <div class="form-row">
        {{ Html::linkAction('AttachmentController@show', $attachment->name, [$attachment->id]) }}
    </div>
    @endforeach

    @php
    $selectedGenres = $post->genres->all();
    @endphp
    <fieldset class="py-2">
        <div class="form-row" data-tag="genres">
            @foreach($selectedGenres as $selectedGenre)
            <div class="form-group col-md-3">
                {{ Form::label('tags[]', 'ジャンル:') }}
                {{ Form::select('tags[]', $genres, $selectedGenre->id, ['class'=> 'form-control', 'placeholder' => 'ジャンルを選択してください']) }}
            </div>
            @endforeach
        </div>
        <button id="genre_adding_button" class="btn btn-primary" type="button">ジャンル追加</button>
    </fieldset>
    @php
    $selectedCasts = $post->casts->all();
    @endphp
    <fieldset class="py-2">
        <div class="form-row" data-tag="casts">
            @foreach($selectedCasts as $selectedCast)
            <div class="form-group col-md-3">
                {{ Form::label('tags[]', '出演者:') }}
                {{ Form::select('tags[]', $casts, $selectedCast->id, ['class'=> 'form-control', 'placeholder' => '出演者を選択してください']) }}
            </div>
            @endforeach
        </div>
        <button id="cast_adding_button" class="btn btn-primary" type="button">出演者追加</button>
    </fieldset>
    {{ Form::submit('更新', ['class' => 'btn btn-primary btn-block']) }}
    {{ Form::close() }}
</div>
<script type="module">
    $(function () {
        $('form').submit(function () {
            $('input[type=submit]').addClass("disabled");
        });

        // 認証用のトークンを送るようにする
        $.ajaxSetup({
            headers: {
                'Authorization': 'Bearer {{ \Auth::user()->api_token }}'
            }
        });

        $('#cast_adding_button').click(function (e) {
            e.preventDefault();

            // タグ枠を追加
            $.get('/api/tag?type=cast').done(function (data) {
                let tagSelect = $('<select class="form-control">').attr('name', 'tags[]');
                tagSelect.append($('<option>').text('出演者を選択してください'));
                data.forEach(function (datum) {
                    let opt = $('<option>').text(datum.name).val(datum.id);
                    opt.appendTo(tagSelect);
                });

                let node = $('<div class="form-group col-md-3">');
                node.append($('<label>').attr('for', 'tags[]').text('出演者:'));
                node.append(tagSelect);
                $('[data-tag="casts"]').append(node);
            });
        });

        $('#genre_adding_button').click(function (e) {
            e.preventDefault();

            $.get('/api/tag?type=genre').done(function (data) {
                let tagSelect = $('<select class="form-control">').attr('name', 'tags[]');
                tagSelect.append($('<option>').text('ジャンルを選択してください'));
                data.forEach(function (datum) {
                    let opt = $('<option>').text(datum.name).val(datum.id);
                    opt.appendTo(tagSelect);
                });

                let node = $('<div class="form-group col-md-3">');
                node.append($('<label>').attr('for', 'tags[]').text('ジャンル:'));
                node.append(tagSelect);
                $('[data-tag="genres"]').append(node);
            });
        });
    });
</script>
@endsection