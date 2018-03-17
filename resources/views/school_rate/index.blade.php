@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学校点评
            </h1>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>点评id</th>
                <th>校区</th>
                <th>社会声誉</th>
                <th>学术水平</th>
                <th>网络服务</th>
                <th>住宿条件</th>
                <th>餐饮质量</th>
                <th>校园地理位置</th>
                <th>校园课外活动</th>
                <th>校园基础设施</th>
                <th>生活幸福指数</th>
                <th>校方与学生群体关系</th>
                <th>文字点评</th>
                <th>点评学生</th>

            </tr>
            @foreach ($rates as $rate)
                <tr>
                    <td>{{$rate->school_rate_id}}</td>
                    <td>{{$rate->schoolDistrict->school_district_name}}</td>
                    <td>{{$rate->social_reputation}}</td>
                    <td>{{$rate->academic_level}}</td>
                    <td>{{$rate->network_services}}</td>
                    <td>{{$rate->accommodation}}</td>
                    <td>{{$rate->food_quality}}</td>
                    <td>{{$rate->campus_location}}</td>
                    <td>{{$rate->extracurricular_activities}}</td>
                    <td>{{$rate->campus_infrastructure}}</td>
                    <td>{{$rate->life_happiness_index}}</td>
                    <td>{{$rate->school_students_relations}}</td>
                    <td>{{$rate->comment}}</td>
                    <td>{{$rate->student->name}}</td>

                </tr>
            @endforeach

        </table>

        {{ $rates->links() }}

    </div>

@stop