@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 教授
            </h1>
            <a href="{{ route('backend.professor.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">

            <form class="form-horizontal" method="post" action="{{route('backend.professor.update.post')}}">
                {{csrf_field()}}

                <input type="hidden" name="professor_id" value="{{$professor->professor_id}}">
                <div class="form-group">
                    <label>教授姓</label>
                    <input type="text" value="{{$professor->professor_fisrt_name}}" class="form-control" name="professor_fisrt_name" placeholder="教授姓">
                </div>
                <div class="form-group">
                    <label>教授名</label>
                    <input type="text" value="{{$professor->professor_second_name}}" class="form-control" name="professor_second_name" placeholder="教授名">
                </div>
                <div class="form-group">
                    <label>教授全名</label>
                    <input type="text" value="{{$professor->professor_full_name}}" class="form-control" name="professor_full_name" placeholder="教授全名">
                </div>
                <div class="form-group">
                    <label>教授个人主页</label>
                    <input type="text" value="{{$professor->professor_web_site}}"  class="form-control" name="professor_web_site" placeholder="教授个人主页">
                </div>

                <div class="form-group">
                    <label>学校</label>
                    <select disabled=disabled class="form-control" name="school_id">
                        <option value="">请选择</option>
                        @foreach ($schools as $school)
                            @if($school->school_id == $professor->school_id)
                                <option selected="selected" value="{{$school->school_id}}">{{$school->school_name}}</option>
                            @else
                                <option value="{{$school->school_id}}">{{$school->school_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>学院</label>
                    <select  disabled=disabled class="form-control" name="college_id">
                        <option value="">请选择</option>
                        @foreach ($colleges as $college)
                            @if($college->college_id == $professor->college_id)
                                <option selected="selected" value="{{$college->college_id}}">{{$college->college_name}}</option>
                            @else
                                <option value="{{$college->college_id}}">{{$college->college_name}}</option>
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

            $('select[name="school_id"]').change(function () {
                var school_id = $(this).val();
                $.post('{{route('backend.get-college-by-school')}}',{school_id:school_id},function (response) {
                    $('select[name="college_id"] option').remove();
                    if(response.data.length == 0){
                        alert("没有找到对应的学院 请添加学院");
                        return ;
                    }
                    $('select[name="college_id"]').append("<option value=\"\">请选择</option>");
                    for(key in response.data) {
                        $('select[name="college_id"]').append("<option value='"+response.data[key].college_id+"'>"+response.data[key].college_name+"</option>");
                    }
                })
            })
        })
    </script>

@stop
