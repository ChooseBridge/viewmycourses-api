@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 城市
            </h1>
            <a href="{{ route('backend.city.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">

            <form class="form-horizontal" method="post" action="{{route('backend.city.update.post')}}">
                {{csrf_field()}}

                <input type="hidden" name="city_id" value="{{$city->city_id}}">

                <div class="form-group">
                    <label>所属国家</label>
                    <select disabled="disabled" class="form-control" name="country_id">
                        <option value="">请选择</option>
                        @foreach ($countrys as $country)
                            @if($country->country_id == $city->country_id)
                                <option selected="selected"
                                        value="{{$country->country_id}}">{{$country->country_name}}</option>
                            @else
                                <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>所属省份</label>
                    <select disabled="disabled" class="form-control" name="province_id">
                        <option value="">请选择</option>
                        @foreach ($provinces as $province)
                            @if($province->province_id == $city->province_id)
                                <option selected="selected"
                                        value="{{$province->province_id}}">{{$province->province_name}}</option>
                            @else
                                <option value="{{$province->province_id}}">{{$province->province_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>城市名称</label>
                    <input type="text" class="form-control" value="{{$city->city_name}}" name="city_name" placeholder="城市名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">编辑</button>
            </form>


        </div>


    </div>

@stop


@section('javascript')
    <script>
        $(function () {

            $('select[name="country_id"]').change(function () {
                var country_id = $(this).val();
                $.post('{{route('geo.get-province-by-country')}}', {country_id: country_id}, function (response) {
                    $('select[name="province_id"] option').remove();
                    if (response.data.length == 0) {
                        alert("没有找到对应的省份 请添加省份");
                        return;
                    }
                    for (key in response.data) {
                        $('select[name="province_id"]').append("<option value='" + response.data[key].province_id + "'>" + response.data[key].province_name + "</option>");
                    }
                })
            })
        })
    </script>

@stop
