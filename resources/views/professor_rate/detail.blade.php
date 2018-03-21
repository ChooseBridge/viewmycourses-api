@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 教授点评详情
            </h1>
        </div>

        <table class="table table-bordered">

            <tr>
                <td>点评ID</td>
                <td>{{$rate->professor_rate_id}}</td>
            </tr>
            <tr>
                <td>教授</td>
                <td>{{$rate->professor->professor_full_name}}</td>
            </tr>
            <tr>
                <td>学校</td>
                <td>{{$rate->professor->school?$rate->professor->school->school_name:''}}</td>
            </tr>
            <tr>
                <td>学院</td>
                <td>{{$rate->professor->college->college_name}}</td>
            </tr>
            <tr>
                <td>课程代码</td>
                <td>{{$rate->course_code}}</td>
            </tr>
            <tr>
                <td>课程名称</td>
                <td>{{$rate->course_name}}</td>
            </tr>
            <tr>
                <td>课程类别</td>
                <td>{{$rate->course_category_name}}</td>
            </tr>
            <tr>
                <td>是否出勤</td>
                <td>{{$rate->attend}}</td>
            </tr>
            <tr>
                <td>课程难度</td>
                <td>{{$rate->difficult_level}}</td>
            </tr>
            <tr>
                <td>笔头作业量</td>
                <td>{{$rate->homework_num}}</td>
            </tr>
            <tr>
                <td>每月考试数</td>
                <td>{{$rate->quiz_num}}</td>
            </tr>
            <tr>
                <td>课程与考试内容相关度</td>
                <td>{{$rate->course_related_quiz}}</td>
            </tr>
            <tr>
                <td>每周课堂外所花总时间</td>
                <td>{{$rate->spend_course_time_at_week}}</td>
            </tr>
            <tr>
                <td>你的成绩</td>
                <td>{{$rate->grade}}</td>
            </tr>
            <tr>
                <td>文字点评</td>
                <td>{{$rate->comment}}</td>
            </tr>
            <tr>
                <td>标签</td>
                <td>{{$rate->tag}}</td>
            </tr>
            <tr>
                <td>点评学生</td>
                <td>{{$rate->student->name}}</td>
            </tr>
            <tr>
                <td>审核状态</td>
                <td>{{$rate->checkStatusName}}</td>
            </tr>
            <tr>
                <td>操作</td>
                <td>
                    @if($rate->check_status == \App\ProfessorRate::PENDING_CHECK)
                        <a class="btn btn-success"
                           href="{{route('backend.professor-rate.aprove.get',['professor_rate_id' => $rate->professor_rate_id])}}">通过</a>
                        <a class="btn btn-danger"
                           href="{{route('backend.professor-rate.reject.get',['professor_rate_id' => $rate->professor_rate_id])}}">拒绝</a>
                    @endif
                </td>

            </tr>
            </tr>



        </table>



    </div>

@stop