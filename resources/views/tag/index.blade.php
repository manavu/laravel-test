@extends('layouts/app')
@section('content')
<div class="container ops-main">
    <div class="row">
        <div class="col-md-12">
            <h3 class="ops-title">{{ $title }} index</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <a href="{{ route($routeName . '.create') }}">create</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ Form::open(['route' => $routeName . '.index', 'method' => 'get', 'class' => 'form-inline']) }}
            <div class="form-group">
                {{ Form::label('keyword', '検索条件') }}
                {{ Form::text('keyword', $keyword, ['class' => 'form-control', 'placeholder' => '内容を入力']) }}
            </div>
            <button class="btn btn-primary" type="submit">検索</button>
            {{ Form::close() }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>@sortablelink('id', 'ID', ['parameter' => 'smile'], ['rel' => 'nofollow']) </th>
                    <th>@sortablelink('name', '指名', ['parameter' => 'smile'], ['rel' => 'nofollow']) </th>
                    <th>@sortablelink('created_at', '作成日時', ['parameter' => 'smile'], ['rel' => 'nofollow']) </th>
                    <th>操作</th>
                </tr>
                @forelse ($casts as $cast)
                <tr>
                    <td>
                        {{ $cast->id }}
                    </td>
                    <td>
                        {{ $cast->name }}
                    </td>
                    <td>
                        {{ $cast->created_at->format('Y年m月d日') }}
                    </td>
                    <td>
                        <a href="{{ route($routeName . '.edit', ['id' => $cast->id]) }}" class="btn btn-primary"
                            role="button">edit</a>
                        {{ Form::open(['route' => [$routeName . '.destroy', $cast->id], 'method' => 'delete']) }}
                        {{ Form::button('delete', ['class' => 'btn btn-danger', 'type' => 'submit']) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">一件もありません</td>
                </tr>
                @endforelse
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ $casts->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection