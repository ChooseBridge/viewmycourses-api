@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学区
            </h1>
            <a href="{{ route('backend.district.add.get') }}" class="btn-success btn">添加学区</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>学区ID</th>
                <th>学区名称</th>
                <th>所属学校</th>
                <th>所属城市</th>
                <th>所属省</th>
                <th>所属国家</th>
                <th>创建者</th>
            </tr>
            @foreach ($districts as $district)
                <tr>
                    <td>{{$district->school_district_id}}</td>
                    <td>{{$district->school_district_name}}</td>
                    <td>{{$district->school->school_name}}</td>
                    <td>{{$district->school->city->city_name}}</td>
                    <td>{{$district->school->province->province_name}}</td>
                    <td>{{$district->school->country->country_name}}</td>
                    @if(!empty($district->student))
                        <td>{{$district->student->student_name}}</td>
                    @elseif(!empty($district->user))
                        <td>{{$district->user->name}}</td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach

        </table>

        {{ $districts->links() }}

    </div>

@stop