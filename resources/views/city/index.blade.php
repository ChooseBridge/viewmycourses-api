@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 城市
            </h1>
            <a href="{{ route('backend.city.add.get') }}" class="btn-success btn">添加城市</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>城市ID</th>
                <th>城市名称</th>
                <th>所属省份</th>
                <th>所属国家</th>
            </tr>
            @foreach ($citys as $city)
                <tr>
                    <td>{{$city->city_id}}</td>
                    <td>{{$city->city_name}}</td>
                    <td>{{$city->province->province_name}}</td>
                    <td>{{$city->country->country_name}}</td>
                </tr>
            @endforeach

        </table>

        {{ $citys->links() }}

    </div>

@stop