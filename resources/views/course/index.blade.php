@extends('voyager::master')

@section('content')

    <div class="alert alert-danger" style="display: none">

    </div>

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 课程编号
            </h1>
            <a href="{{ route('backend.course.add.get') }}" class="btn-success btn">添加课程编号</a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>课程编号</th>
                <th>教授</th>
                <th>操作</th>
            </tr>
            @foreach ($courses as $course)
                <tr>

                    <td>{{$course->course_id}}</td>
                    <td>{{$course->course_code}}</td>
                    <td>{{$course->professor?$course->professor->professor_full_name:""}}</td>



                    <td>
                        <a class="btn btn-info"
                           href="{{route('backend.course.update.get',['course_id' => $course->course_id])}}">编辑</a>
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $courses->links() }}

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