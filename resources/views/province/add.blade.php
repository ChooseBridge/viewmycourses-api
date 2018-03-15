@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 省份
            </h1>
            <a href="{{ route('backend.province.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.province.add.post')}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label>所属国家</label>
                    <select class="form-control" name="country_id">
                        @foreach ($countrys as $country)
                            <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>省份名称</label>
                    <input type="text" class="form-control" name="province_name" placeholder="省份名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">创建</button>
            </form>


        </div>


    </div>

@stop