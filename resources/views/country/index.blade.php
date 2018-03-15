@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 国家
            </h1>
            <a href="{{ route('backend.country.add.get') }}" class="btn-success btn">添加国家</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>国家ID</th>
                <th>国家名称</th>
            </tr>
            @foreach ($countrys as $country)
                <tr>
                    <td>{{$country->country_id}}</td>
                    <td>{{$country->country_name}}</td>
                </tr>
            @endforeach

        </table>

        {{ $countrys->links() }}

    </div>

@stop