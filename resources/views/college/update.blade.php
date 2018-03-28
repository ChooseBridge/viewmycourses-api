@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学院
            </h1>
            <a href="{{ route('backend.college.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">


            <form class="form-horizontal" method="post" action="{{route('backend.college.update.post')}}">
                {{csrf_field()}}

                <input type="hidden" name="college_id" value="{{$college->college_id}}">
                <div class="form-group">
                    <label>所属学校</label>
                    <select disabled="disabled" class="form-control" name="school_id">
                        <option value="">请选择</option>
                        @foreach ($schools as $countryName => $subSchools)
                            <option disabled="disabled" value="">{{$countryName}}</option>
                            @foreach ($subSchools as $school)
                                @if($college->school_id == $school['school_id'])
                                    <option selected="selected" value="{{$school['school_id']}}">-------{{$school['school_name']}}</option>
                                @else
                                    <option value="{{$school['school_id']}}">-------{{$school['school_name']}}</option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>学院名称</label>
                    <input type="text" class="form-control" value="{{$college->college_name}}" name="college_name" placeholder="学院名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">编辑</button>
            </form>


        </div>


    </div>

@stop



