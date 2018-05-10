@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学校
            </h1>
            <a href="{{ route('backend.school.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">

            <form class="form-horizontal" method="post" action="{{route('backend.school.update.post')}}">
                {{csrf_field()}}

                <input type="hidden" name="school_id" value="{{$school->school_id}}">

                <div class="form-group">
                    <label>学校名称</label>
                    <input type="text" value="{{$school->school_name}}" class="form-control" name="school_name" placeholder="学校名称">
                </div>
                <div class="form-group">
                    <label>学校昵称</label>
                    <input type="text" value="{{$school->school_nick_name}}" class="form-control" name="school_nick_name" placeholder="学校昵称">
                </div>
                <div class="form-group">
                    <label>学校昵称2</label>
                    <input type="text" value="{{$school->school_nick_name_two}}" class="form-control" value="" name="school_nick_name_two" placeholder="学校昵称2">
                </div>
                <div class="form-group">
                    <label>网站地址</label>
                    <input type="text"  value="{{$school->website_url}}" class="form-control" name="website_url" placeholder="网站地址">
                </div>
                <div class="form-group">
                    <label>所属国家</label>
                    <select disabled="disabled" class="form-control" name="country_id">
                        <option value="">请选择</option>
                        @foreach ($countrys as $country)
                            @if($country->country_id == $school->country_id)
                                <option selected="selected" value="{{$country->country_id}}">{{$country->country_name}}</option>
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
                            @if($province->province_id == $school->province_id)
                                <option selected="selected" value="{{$province->province_id}}">{{$province->province_name}}</option>
                            @else
                                <option value="{{$province->province_id}}">{{$province->province_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>所属城市</label>
                    <select  class="form-control" name="city_id">
                        <option value="">请选择</option>
                        @foreach ($citys as $city)
                            @if($city->city_id == $school->city_id)
                                <option selected="selected" value="{{$city->city_id}}">{{$city->city_name}}</option>
                            @else
                                <option value="{{$city->city_id}}">{{$city->city_name}}</option>
                            @endif
                        @endforeach
                    </select>
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
                    $('select[name="province_id"]').append("<option value=\"\">请选择</option>");
                    for (key in response.data) {
                        $('select[name="province_id"]').append("<option value='" + response.data[key].province_id + "'>" + response.data[key].province_name + "</option>");
                    }
                })
            });

            $('select[name="province_id"]').change(function () {
                var province_id = $(this).val();
                $.post('{{route('geo.get-city-by-province')}}', {province_id: province_id}, function (response) {
                    $('select[name="city_id"] option').remove();
                    if (response.data.length == 0) {
                        alert("没有找到对应的城市 请添加城市");
                        return;
                    }

                    $('select[name="city_id"]').append("<option value=\"\">请选择</option>");
                    for (key in response.data) {
                        $('select[name="city_id"]').append("<option value='" + response.data[key].city_id + "'>" + response.data[key].city_name + "</option>");
                    }
                })
            })
        })
    </script>

@stop
