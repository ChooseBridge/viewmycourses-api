@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 教授修正评论
            </h1>
            <a href="{{ route('backend.professor.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">
            <ul class="list-group">
                @if($professor->comments->toArray())
                    @foreach ($professor->comments as $comment)
                        <li class="list-group-item">{{$comment->comment}}</li>
                    @endforeach
                @else
                    <li class="list-group-item">没有修正信息</li>
                @endif

            </ul>
        </div>



    </div>

@stop