@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 教授
            </h1>
            <a href="{{ route('backend.professor.add.get') }}" class="btn-success btn">添加教授</a>
        </div>

        <div class="row">

            <form>
                <div class="col-xs-4">
                    <select name="check_status" class="form-control">
                        <option value="">请选择</option>
                        @if($check_status == 0)
                            <option selected="selected" value="0">待审核</option>
                        @else
                            <option value="0">待审核</option>
                        @endif

                        @if($check_status == 1)
                            <option selected="selected" value="1">已审核</option>
                        @else
                            <option value="1">已审核</option>
                        @endif


                    </select>
                </div>
                <div class="col-xs-4">
                    <input type="submit" class="btn-sm btn-info" value="查询">
                </div>
            </form>
        </div>

        <br>


        <table class="table table-bordered">
            <tr>
                <th>教授ID</th>
                <th>教授姓名</th>
                <th>教授个人主页</th>
                <th>所属学校</th>
                <th>所属学院</th>
                <th>创建者</th>
                <th>审核状态</th>
                <th>操作</th>
            </tr>
            @foreach ($professors as $professor)
                <tr>
                    <td>{{$professor->professor_id}}</td>
                    <td>{{$professor->professor_full_name}}</td>
                    <td>{{$professor->professor_web_site}}</td>
                    @if($professor->school)
                        <td>{{$professor->school->school_name}}</td>
                    @else
                        <td></td>
                    @endif

                    <td>{{$professor->college->college_name}}</td>
                    @if(!empty($professor->student))
                        <td>{{$professor->student->name}}</td>
                    @elseif(!empty($professor->user))
                        <td>{{$professor->user->name}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$professor->checkStatusName}}</td>
                    <td>
                        <a class="btn btn-info"
                           href="{{route('backend.professor.update.get',['professor_id' => $professor->professor_id])}}">编辑</a>
                        {{--<a class="btn btn-success"--}}
                           {{--href="{{route('backend.professor.show-comment.get',['professor_id' => $professor->professor_id])}}">修正</a>--}}

                    @if($professor->check_status == \App\Professor::PENDING_CHECK)
                            <a class="btn btn-success"
                               href="{{route('backend.professor.aprove.get',['professor_id' => $professor->professor_id])}}">通过</a>
                            <a class="btn btn-danger"
                               href="{{route('backend.professor.reject.get',['professor_id' => $professor->professor_id])}}">拒绝</a>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

        {{ $professors->links() }}

    </div>

@stop