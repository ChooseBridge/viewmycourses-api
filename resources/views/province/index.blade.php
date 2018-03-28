@extends('voyager::master')

@section('content')

    <div class="alert alert-danger" style="display: none">

    </div>

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
                <th>操作</th>
            </tr>
            @foreach ($provinces as $province)
                <tr>
                    <td>{{$province->province_id}}</td>
                    <td>{{$province->province_name}}</td>
                    <td>{{$province->country->country_name}}</td>
                    <td>
                        <a class="btn btn-info"
                           href="{{route('backend.province.update.get',['province_id' => $province->province_id])}}">编辑</a>
                        <button class="btn btn-danger delete-button"
                                src="{{route('backend.province.delete.post',['province_id' => $province->province_id])}}">删除
                        </button>
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $provinces->links() }}

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
