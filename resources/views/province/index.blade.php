@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 省份
            </h1>
            <a href="{{ route('backend.province.add.get') }}" class="btn-success btn">添加省份</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>省份ID</th>
                <th>省份名称</th>
                <th>所属国家</th>
            </tr>
            @foreach ($provinces as $province)
                <tr>
                    <td>{{$province->province_id}}</td>
                    <td>{{$province->province_name}}</td>
                    <td>{{$province->country->country_name}}</td>
                </tr>
            @endforeach

        </table>

        {{ $provinces->links() }}

    </div>

@stop