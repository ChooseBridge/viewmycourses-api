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
            <form class="form-horizontal" method="post" action="{{route('backend.course.add.post')}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label>教授</label>
                    <select class="form-control" name="professor_id">
                        <option value="">请选择</option>
                        @foreach ($professors as $professor)
                            <option value="{{$professor->professor_id}}">{{$professor->professor_full_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>课程code</label>
                    <input type="text" class="form-control" name="course_code" placeholder="">
                </div>

                <button type="submit" class="btn btn-success pull-right">创建</button>
            </form>


        </div>


    </div>

@stop



