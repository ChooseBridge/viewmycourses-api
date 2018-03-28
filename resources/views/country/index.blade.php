@extends('voyager::master')

@section('content')

    <div class="alert alert-danger" style="display: none">

    </div>


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
                <th>操作</th>
            </tr>
            @foreach ($countrys as $country)
                <tr>
                    <td>{{$country->country_id}}</td>
                    <td>{{$country->country_name}}</td>
                    <td>
                        <a class="btn btn-info"
                           href="{{route('backend.country.update.get',['country_id' => $country->country_id])}}">编辑</a>
                        <button class="btn btn-danger delete-button"
                                src="{{route('backend.country.delete.post',['country_id' => $country->country_id])}}">删除
                        </button>
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $countrys->links() }}

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