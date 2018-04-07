@extends('voyager::master')

@section('content')

    <div class="alert alert-danger" style="display: none">

    </div>

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 课程类别
            </h1>
            <a href="{{ route('backend.course-category.add.get') }}" class="btn-success btn">添加课程类别</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>分类ID</th>
                <th>学校</th>
                <th>分类名</th>
                <th>操作</th>
            </tr>
            @foreach ($courseCategorys as $courseCategory)
                <tr>
                    <td>{{$courseCategory->course_category_id}}</td>
                    <td>{{$courseCategory->school->school_name}}</td>
                    <td>{{$courseCategory->course_category_name}}</td>
                    <td>
                        <a class="btn btn-info"
                           href="{{route('backend.course-category.update.get',['course_category_id' => $courseCategory->course_category_id])}}">编辑</a>

                    </td>
                </tr>
            @endforeach

        </table>

        {{ $courseCategorys->links() }}

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