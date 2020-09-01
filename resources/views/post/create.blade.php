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
    <div class="form-row">
        <div class="col-md-12">
            {!! Form::open(['url' => 'post', 'method' => 'post', 'files' => true]) !!}
            <div class="form-group">
                {!! Form::label('context', '内容:') !!}
                {!! Form::text('context', '', ['class' => 'form-control', 'placeholder' => ''] ) !!}
                @error('context')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                {!! Form::label('image', '添付ファイル:') !!}
                {!! Form::file('image') !!}
            </div>
            {{-- @for ディレクティブを使用するとフォーマッターが原因で微妙にずれる --}}
            @for($i = 0; $i < 3; $i++) <div class="form-group">
                {!! Form::label('tags[]', 'タグ:') !!}
                {!! Form::select('tags[]', $tags, null, ['placeholder' => 'タグを選択してください']) !!}
        </div>
        @endfor
        {!! Form::submit('作成', ['class' => 'btn btn-primary btn-block']) !!}
        {!! Form::close() !!}
    </div>
</div>
</div>
@endsection