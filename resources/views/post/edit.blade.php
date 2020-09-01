@extends('layouts/app')
@section('content')
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
    <div class="form-row">
        <div class="col-md-12">
            {!! Form::open(['action' => ['PostController@update', $post->id], 'method' => 'put']) !!}
            <div class="form-group">
                {!! Form::label('context', '内容:') !!}
                {!! Form::text('context', $post->context, ['class' => 'form-control', 'placeholder' => ''] ) !!}
                @error('context')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            @foreach ($post->attachments()->get() as $attachment)
            <div>
                {{ Html::linkAction('AttachmentController@show', $attachment->name, [$attachment->id]) }}
            </div>
            @endforeach
            {{-- @for ディレクティブを使用するとフォーマッターが原因で微妙にずれる --}}
            @for($i = 0; $i < 3; $i++) <div class="form-group">
                @php
                // この方法ではクエリがループの回数だけ呼ばれてしまうので別な方法がよい
                $tag = $post->tags()->skip($i)->first();
                $defaultValue = is_null($tag) ? null : $tag->id;
                @endphp
                {!! Form::label('tags[]', 'タグ:') !!}
                {!! Form::select('tags[]', $tags, $defaultValue, ['placeholder' => 'タグを選択してください']) !!}
        </div>
        @endfor
        {!! Form::submit('更新', ['class' => 'btn btn-primary btn-block']) !!}
        {!! Form::close() !!}
    </div>
</div>
</div>
@endsection