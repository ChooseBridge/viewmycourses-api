@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学校
            </h1>
            <a href="{{ route('backend.school.add.get') }}" class="btn-success btn">添加学校</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>学校ID</th>
                <th>学校名称</th>
                <th>学校昵称</th>
                <th>所属国家</th>
                <th>所属省份</th>
                <th>所属城市</th>
                <th>网站地址</th>
                <th>学生email</th>
                <th>审核状态</th>
                <th>创建者</th>
                <th>操作</th>
            </tr>
            @foreach ($schools as $school)
                <tr>
                    <td>{{$school->school_id}}</td>
                    <td>{{$school->school_name}}</td>
                    <td>{{$school->school_nick_name}}</td>
                    <td>{{$school->country->country_name}}</td>
                    <td>{{$school->province->province_name}}</td>
                    <td>{{$school->city->city_name}}</td>
                    <td>{{$school->website_url}}</td>
                    <td>{{$school->your_email}}</td>
                    <td>{{$school->checkStatusName}}</td>
                    @if(!empty($school->student))
                        <td>{{$school->student->name}}</td>
                    @elseif(!empty($school->user))
                        <td>{{$school->user->name}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>
                        @if($school->check_status == \App\School::PENDING_CHECK)
                            <a class="btn btn-success"
                               href="{{route('backend.school.aprove.get',['school_id' => $school->school_id])}}">通过</a>
                            <a class="btn btn-danger"
                               href="{{route('backend.school.reject.get',['school_id' => $school->school_id])}}">拒绝</a>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $schools->links() }}

    </div>

@stop