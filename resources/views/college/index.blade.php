@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学院
            </h1>
            <a href="{{ route('backend.college.add.get') }}" class="btn-success btn">添加学院</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>学院ID</th>
                <th>学院名称</th>
                <th>所属学校</th>
                <th>所属城市</th>
                <th>所属省</th>
                <th>所属国家</th>
                <th>创建者</th>
                <th>操作</th>
            </tr>
            @foreach ($colleges as $college)
                <tr>
                    <td>{{$college->college_id}}</td>
                    <td>{{$college->college_name}}</td>
                    <td>{{$college->school->school_name}}</td>
                    <td>{{$college->school->city->city_name}}</td>
                    <td>{{$college->school->province->province_name}}</td>
                    <td>{{$college->school->country->country_name}}</td>
                    @if(!empty($college->student))
                        <td>{{$college->student->student_name}}</td>
                    @elseif(!empty($college->user))
                        <td>{{$college->user->name}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>
                        <a class="btn btn-info"
                           href="{{route('backend.college.update.get',['college_id' => $college->college_id])}}">编辑</a>
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $colleges->links() }}

    </div>

@stop