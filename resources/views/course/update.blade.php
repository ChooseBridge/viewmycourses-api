@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 课程编号
            </h1>
            <a href="{{ route('backend.course.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.course.update.post')}}">
                {{csrf_field()}}
                <input type="hidden" value="{{$course->course_id}}" name="course_id">
                <div class="form-group">
                    <label>所属学校</label>
                    <select class="form-control" name="school_id">
                        <option value="">请选择</option>
                        @foreach ($professors as $professor)
                            @if($course->professor_id == $professor->professor_id)
                                <option selected=selected value="{{$professor->professor_id}}">{{$professor->professor_full_name}}</option>
                            @else
                                <option value="{{$professor->professor_id}}">{{$professor->professor_full_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>课程编号</label>
                    <input type="text" value="{{$course->course_code}}" class="form-control" name="course_code" placeholder="课程编号">
                </div>

                <button type="submit" class="btn btn-success pull-right">编辑</button>
            </form>


        </div>


    </div>

@stop



