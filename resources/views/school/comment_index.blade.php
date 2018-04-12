@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学校反馈
            </h1>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>反馈ID</th>
                <th>学校</th>
                <th>反馈信息</th>
                <th>创建者</th>
                <th>创建时间</th>
            </tr>
            @foreach ($comments as $comment)
                <tr>
                    <td>{{$comment->comment_id}}</td>
                    <td>
                        @if($comment->school)
                            <a href="{{route('backend.school.update.post',['school_id'=>$comment->school->school_id])}}">{{$comment->school->school_name}}</a>
                        @endif
                    </td>
                    <td>{{$comment->comment}}</td>
                    <td>{{$comment->student?$comment->student->name:""}}</td>
                    <td>{{$comment->created_at}}</td>

                </tr>
            @endforeach

        </table>

        {{ $comments->links() }}

    </div>

@stop