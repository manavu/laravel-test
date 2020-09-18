@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">{{Html::link('/post', 'post')}}</li>
                    <li class="list-group-item">{{Html::link('/cast', 'cast')}}</li>
                    <li class="list-group-item">{{Html::link('/genre', 'genre')}}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection