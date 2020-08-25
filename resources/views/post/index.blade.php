@extends('shared/layout')
@section('content')
<div class="container ops-main">
    <div class="row">
        <div class="col-md-12">
            <h3 class="ops-title">Posts index</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <a href="/post/create">create</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>内容</th>
                    <th>作成日時</th>
                    <th>操作</th>
                </tr>
                @forelse ($posts as $post)
                <tr>
                    <td>
                        {{ $post->context }}
                    </td>
                    <td>
                        {{ $post->created_at->format('Y年m月d日') }}
                    </td>
                    <td>
                        <!-- <a href="{{ url('/post', $post->id) }}" class="btn btn-primary" role="button">edit</a> -->
                        <a href="{{ action('PostController@edit', ['id' => $post->id]) }}" class="btn btn-primary"
                            role="button">edit</a>
                        {!! Form::open(['action' => ['PostController@destroy', $post->id], 'method' => 'delete']) !!}
                        {!! Form::button('delete', ['class' => 'btn btn-danger', 'type' => 'submit']) !!}
                        {!! Form::close() !!}
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
</div>
@endsection