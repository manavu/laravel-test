@extends('layouts/app')
@section('content')
<div class="container ops-main">
    <div class="row">
        <div class="col-md-12">
            <h3 class="ops-title">Post Create</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ Html::link('/post', '一覧へ')}}
        </div>
    </div>

    {{ Form::open(['url' => 'post', 'method' => 'post', 'files' => true]) }}
    <div class="form-group form-row">
        {{ Form::label('context', '内容:') }}
        {{ Form::text('context', '', ['class' => 'form-control', 'placeholder' => ''] ) }}
        @error('context')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group form-row">
        {{ Form::label('image', '添付ファイル:') }}
        {{ Form::file('image') }}
    </div>
    <div class="form-row">
        {{-- @for ディレクティブを使用するとフォーマッターが原因で微妙にずれる --}}
        @for ($i = 0; $i < 4; $i++) <div class="form-group col-md-3">
            {{ Form::label('tags[]', 'ジャンル:') }}
            {{ Form::select('tags[]', $genres, '', ['class'=> 'form-control', 'placeholder' => 'ジャンルを選択してください']) }}
    </div>
    @endfor
</div>
<div class="form-row">
    @for ($i = 0; $i < 4; $i++) <div class="form-group col-md-3">
        {{ Form::label('tags[]', '出演者:') }}
        {{ Form::select('tags[]', $casts, '', ['class'=> 'form-control', 'placeholder' => '出演者を選択してください']) }}
</div>
@endfor
</div>

<div class="form-group row">
    {{ Form::submit('作成', ['class' => 'btn btn-primary btn-block']) }}
</div>
{{ Form::close() }}
</div>
<script type="module">
    $(function () {
        $('form').submit(function () {
            $('input[type=submit]').addClass("disabled");
        });
    });
</script>
@endsection