@extends('voyager::master')

@section('content')

    <div class="alert alert-danger" style="display: none">

    </div>

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
                <th>操作</th>
            </tr>
            @foreach ($citys as $city)
                <tr>
                    <td>{{$city->city_id}}</td>
                    <td>{{$city->city_name}}</td>
                    <td>{{$city->province->province_name}}</td>
                    <td>{{$city->country->country_name}}</td>
                    <td>
                        <a class="btn btn-info"
                           href="{{route('backend.city.update.get',['city_id' => $city->city_id])}}">编辑</a>
                        <button class="btn btn-danger delete-button"
                                src="{{route('backend.city.delete.post',['city_id' => $city->city_id])}}">删除
                        </button>
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $citys->links() }}

    </div>

@stop


@section('javascript')
    <script>
        $(function () {

            $('.delete-button').on('click', function () {
                var src = $(this).attr('src');
                var r = confirm("确定删除改项  删除后不可恢复");
                if (r) {
                    $.post(src, function (data) {
                        data =  $.parseJSON(data);
                        if (data.success) {
                            window.location.reload();
                        } else {
                            $('.alert-danger').text(data.message).fadeIn(3000).fadeOut(3000);
                        }
                    })
                }
            })

        })
    </script>
@stop