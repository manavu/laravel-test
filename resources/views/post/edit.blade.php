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
    <div class="row">
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
            {!! Form::submit('更新', ['class' => 'btn btn-primary btn-block']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection