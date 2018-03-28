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

            <form class="form-horizontal" method="post" action="{{route('backend.province.update.post')}}">
                {{csrf_field()}}
                <input type="hidden" name="province_id" value="{{$province->province_id}}">
                <div class="form-group">
                    <label>所属国家</label>
                    <select disabled="disabled" class="form-control" name="country_id">
                        @foreach ($countrys as $country)
                            @if($country->country_id == $province->country_id)
                                <option selected=selected value="{{$country->country_id}}">{{$country->country_name}}</option>
                            @else
                                <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>省份名称</label>
                    <input type="text" class="form-control" value="{{$province->province_name}}" name="province_name" placeholder="省份名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">编辑</button>
            </form>


        </div>


    </div>

@stop