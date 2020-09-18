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
    {{ Form::open(['action' => ['PostController@update', $post->id], 'method' => 'put']) }}
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
    $selectedGenres = $post->genres->take(4);
    @endphp
    <div class="form-row">
        {{-- @for ディレクティブを使用するとフォーマッターが原因で微妙にずれる --}}
        @for ($i = 0; $i < 4; $i++) <div class="form-group col-md-3">
            {{ Form::label('genres[]', 'ジャンル:') }}
            @if ($i < $selectedGenres->count())
                {{ Form::select('tags[]', $genres, $selectedGenres[$i]->id, ['class'=> 'form-control', 'placeholder' => 'ジャンルを選択してください']) }}
                @else
                {{ Form::select('tags[]', $genres, '', ['class'=> 'form-control', 'placeholder' => 'ジャンルを選択してください']) }}
                @endif
    </div>
    @endfor
</div>

@php
$selectedCasts = $post->casts->take(4);
@endphp
<div class="form-row">
    @for ($i = 0; $i < 4; $i++) <div class="form-group col-md-3">
        {{ Form::label('genres[]', '出演者:') }}
        @if ($i < $selectedCasts->count())
            {{ Form::select('tags[]', $casts, $selectedCasts[$i]->id, ['class'=> 'form-control', 'placeholder' => '出演者を選択してください']) }}
            @else
            {{ Form::select('tags[]', $casts, '', ['class'=> 'form-control', 'placeholder' => '出演者を選択してください']) }}
            @endif
</div>
@endfor
</div>

<div class="form-group row">
    {{ Form::submit('更新', ['class' => 'btn btn-primary btn-block']) }}
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