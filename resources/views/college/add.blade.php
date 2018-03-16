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
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.college.add.post')}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label>所属学校</label>
                    <select class="form-control" name="school_id">
                        <option value="">请选择</option>
                        @foreach ($schools as $countryName => $subSchools)
                            <option value="">{{$countryName}}</option>
                            @foreach ($subSchools as $school)
                                <option value="{{$school['school_id']}}">-------{{$school['school_name']}}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>学院名称</label>
                    <input type="text" class="form-control" name="college_name" placeholder="学院名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">创建</button>
            </form>


        </div>


    </div>

@stop



