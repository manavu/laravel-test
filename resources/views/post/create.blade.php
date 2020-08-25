@extends('shared/layout')
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
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['url' => 'post', 'method' => 'post']) !!}
            <div class="form-group">
                {!! Form::label('context', '内容:') !!}
                {!! Form::text('context', '', ['class' => 'form-control', 'placeholder' => ''] ) !!}
                @error('context')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            {!! Form::submit('作成', ['class' => 'btn btn-primary btn-block']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection