@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 教授点评
            </h1>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>点评ID</th>
                <th>教授</th>
                <th>学校</th>
                <th>学院</th>
                <th>课程代码</th>
                <th>课程名称</th>
                <th>课程类别</th>
                <th>是否出勤</th>
                <th>课程难度</th>
                <th>笔头作业量</th>
                <th>每月考试数</th>
                <th>课程与考试内容相关度</th>
                <th>每周课堂外所花总时间</th>
                <th>你的成绩</th>
                <th>文字点评</th>
                <th>标签</th>
                <th>点评学生</th>
            </tr>
            @foreach ($rates as $rate)
                <tr>
                    <td>{{$rate->professor_rate_id}}</td>
                    <td>{{$rate->professor->professor_full_name}}</td>
                    <td>{{$rate->professor->school->school_name}}</td>
                    <td>{{$rate->professor->college->college_name}}</td>
                    <td>{{$rate->course_code}}</td>
                    <td>{{$rate->course_name}}</td>
                    <td>{{$rate->course_category_name}}</td>
                    <td>{{$rate->attend}}</td>
                    <td>{{$rate->difficult_level}}</td>
                    <td>{{$rate->homework_num}}</td>
                    <td>{{$rate->quiz_num}}</td>
                    <td>{{$rate->course_related_quiz}}</td>
                    <td>{{$rate->spend_course_time_at_week}}</td>
                    <td>{{$rate->grade}}</td>
                    <td>{{$rate->comment}}</td>
                    <td>{{$rate->tag}}</td>
                    <td>{{$rate->student->name}}</td>

                </tr>
            @endforeach

        </table>

        {{ $rates->links() }}

    </div>

@stop