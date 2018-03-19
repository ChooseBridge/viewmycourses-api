@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学校
            </h1>
            <a href="{{ route('backend.city.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.school.add.post')}}">
                {{csrf_field()}}

                <div class="form-group">
                    <label>学校名称</label>
                    <input type="text" class="form-control" name="school_name" placeholder="学校名称">
                </div>
                <div class="form-group">
                    <label>学校昵称</label>
                    <input type="text" class="form-control" name="school_nick_name" placeholder="学校昵称">
                </div>
                <div class="form-group">
                    <label>学校昵称2</label>
                    <input type="text" class="form-control" value="" name="school_nick_name_two" placeholder="学校昵称2">
                </div>
                <div class="form-group">
                    <label>网站地址</label>
                    <input type="text" class="form-control" name="website_url" placeholder="网站地址">
                </div>
                <div class="form-group">
                    <label>所属国家</label>
                    <select class="form-control" name="country_id">
                        <option value="">请选择</option>
                        @foreach ($countrys as $country)
                            <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>所属省份</label>
                    <select class="form-control" name="province_id">
                        <option value="">请选择</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>所属城市</label>
                    <select class="form-control" name="city_id">
                        <option value="">请选择</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success pull-right">创建</button>
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
