@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 教授点评
            </h1>
            <a href="{{ route('backend.professor-rate.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.professor-rate.update.post')}}">
                {{csrf_field()}}

                <input type="hidden" name="professor_rate_id" value="{{$rate->professor_rate_id}}">


                <div class="form-group">
                <label>课程名称</label>
                <input type="text" value="{{$rate->course_name}}" class="form-control" name="course_name" placeholder="">
                </div>


                <div class="form-group">
                    <label>课程编号</label>
                    @if($rate->course_id == 0)
                        <span class="text-warning">用户自行添加的code为{{$rate->course_code}},请选择系统中code</span>
                    @endif
                    <select class="form-control" name="course_id">
                        <option>请选择</option>
                        @foreach ($courseCodes as $courseCode)
                            @if($rate->course_id == $courseCode['course_id'])
                                <option selected="selected" value="{{$courseCode['course_id']}}">{{$courseCode['course_code']}}</option>
                            @else
                                <option value="{{$courseCode['course_id']}}">{{$courseCode['course_code']}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>课程分类</label>
                    @if($rate->course_category_id == 0)
                        <span class="text-warning">用户自行添加的分类为{{$rate->course_category_name}},请选择系统中分类</span>
                    @endif
                    <select class="form-control" name="course_category_id">
                        <option>请选择</option>
                        @foreach ($courseCategorys as $courseCategory)
                            @if($rate->course_category_id == $courseCategory['course_category_id'])
                                <option selected="selected" value="{{$courseCategory['course_category_id']}}">{{$courseCategory['course_category_name']}}</option>
                            @else
                                <option value="{{$courseCategory['course_category_id']}}">{{$courseCategory['course_category_name']}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>文字点评</label>
                    <textarea   class="form-control" name="comment" placeholder="">{{$rate->comment}}
                    </textarea>
                </div>


                <button type="submit" class="btn btn-success pull-right">编辑</button>
            </form>


        </div>


    </div>

@stop



