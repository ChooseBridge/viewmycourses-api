@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 教授
            </h1>
            <a href="{{ route('backend.professor.add.get') }}" class="btn-success btn">添加教授</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>教授ID</th>
                <th>教授姓名</th>
                <th>教授个人主页</th>
                <th>所属学校</th>
                <th>所属学院</th>
                <th>创建者</th>
                <th>审核状态</th>
            </tr>
            @foreach ($professors as $professor)
                <tr>
                    <td>{{$professor->professor_id}}</td>
                    <td>{{$professor->professor_full_name}}</td>
                    <td>{{$professor->professor_web_site}}</td>
                    <td>{{$professor->school->school_name}}</td>
                    <td>{{$professor->college->college_name}}</td>
                    @if(!empty($professor->student))
                        <td>{{$professor->student->student_name}}</td>
                    @elseif(!empty($professor->user))
                        <td>{{$professor->user->name}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$professor->checkStatusName}}</td>
                    <td>
                        @if($professor->check_status == \App\Professor::PENDING_CHECK)
                            <a class="btn btn-success"
                               href="{{route('backend.professor.aprove.get',['school_id' => $professor->professor_id])}}">通过</a>
                            <a class="btn btn-danger"
                               href="{{route('backend.professor.reject.get',['school_id' => $professor->professor_id])}}">拒绝</a>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $professors->links() }}

    </div>

@stop