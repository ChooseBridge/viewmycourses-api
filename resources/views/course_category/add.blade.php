@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 课程类别
            </h1>
            <a href="{{ route('backend.course-category.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.course-category.add.post')}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label>所属学校</label>
                    <select class="form-control" name="school_id">
                        <option value="">请选择</option>
                        @foreach ($schools as $school)
                            <option value="{{$school->school_id}}">{{$school->school_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>课程类别名称</label>
                    <input type="text" class="form-control" name="course_category_name" placeholder="课程类别名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">创建</button>
            </form>


        </div>


    </div>

@stop



