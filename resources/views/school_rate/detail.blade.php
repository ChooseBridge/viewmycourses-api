@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学校点评详情
            </h1>
        </div>

        <table class="table table-bordered">

            <tr>
                <th>点评id</th>
                <td>{{$rate->school_rate_id}}</td>
            </tr>
            <tr>
                <td>校区</td>
                <td>{{$rate->schoolDistrict->school_district_name}}</td>
            </tr>
            <tr>
                <td>社会声誉</td>
                <td>{{$rate->social_reputation}}</td>

            </tr>
            <tr>
                <td>学术水平</td>
                <td>{{$rate->academic_level}}</td>
            </tr>
            <tr>
                <td>网络服务</td>
                <td>{{$rate->network_services}}</td>
            </tr>
            <tr>
                <td>住宿条件</td>
                <td>{{$rate->accommodation}}</td>
            </tr>
            <tr>
                <td>餐饮质量</td>
                <td>{{$rate->food_quality}}</td>
            </tr>
            <tr>
                <td>校园地理位置</td>
                <td>{{$rate->campus_location}}</td>
            </tr>
            <tr>
                <td>校园课外活动</td>
                <td>{{$rate->extracurricular_activities}}</td>
            </tr>
            <tr>
                <td>校园基础设施</td>
                <td>{{$rate->campus_infrastructure}}</td>
            </tr>
            <tr>
                <td>生活幸福指数</td>
                <td>{{$rate->life_happiness_index}}</td>
            <tr>
                <td>校方与学生群体关系</td>
                <td>{{$rate->school_students_relations}}</td>
            </tr>
            <tr>
                <th>文字点评</th>
                <td>{{$rate->comment}}</td>
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
                    @if($rate->check_status == \App\SchoolRate::PENDING_CHECK)
                        <a class="btn btn-success"
                           href="{{route('backend.school-rate.aprove.get',['school_rate_id' => $rate->school_rate_id])}}">通过</a>
                        <a class="btn btn-danger"
                           href="{{route('backend.school-rate.reject.get',['school_rate_id' => $rate->school_rate_id])}}">拒绝</a>
                    @endif
                </td>
            </tr>

        </table>


    </div>

@stop