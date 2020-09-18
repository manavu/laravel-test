@extends('layouts/app')
@section('content')
<div class="container ops-main">
    <div class="row">
        <div class="col-md-12">
            <h3 class="ops-title">{{ $title }} Create</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ Html::linkRoute($routeName . '.index', '一覧へ')}}
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12">
            {{ Form::open(['route' => $routeName . '.store', 'method' => 'post', 'files' => false]) }}
            <div class="form-group">
                {{ Form::label('name', '名称:') }}
                {{ Form::text('name', '', ['class' => 'form-control', 'placeholder' => ''] ) }}
                @error('context')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        {{ Form::submit('作成', ['class' => 'btn btn-primary btn-block']) }}
        {{ Form::close() }}
    </div>
</div>
</div>
<script type="module">
    $(function () {
        $('form').submit(function () {
            $('input[type=submit]').addClass("disabled");
        });
    });
</script>
@endsection