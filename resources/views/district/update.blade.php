@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 学区
            </h1>
            <a href="{{ route('backend.district.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.district.update.post')}}">
                {{csrf_field()}}

                <input type="hidden" name="school_district_id" value="{{$district->school_district_id}}">
                <div class="form-group">
                    <label>所属学校</label>
                    <select disabled="disabled" class="form-control" name="school_id">
                        <option disabled="disabled" value="">请选择</option>
                        @foreach ($schools as $countryName => $subSchools)
                            <option value="">{{$countryName}}</option>
                            @foreach ($subSchools as $school)
                                @if($school['school_id'] == $district->school_id)
                                    <option selected="selected" value="{{$school['school_id']}}">-------{{$school['school_name']}}</option>
                                @else
                                    <option value="{{$school['school_id']}}">-------{{$school['school_name']}}</option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>学区名称</label>
                    <input type="text" class="form-control"  value="{{$district->school_district_name}}" name="school_district_name" placeholder="学院名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">编辑</button>
            </form>


        </div>


    </div>

@stop



