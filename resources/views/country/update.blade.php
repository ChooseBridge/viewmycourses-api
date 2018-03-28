@extends('voyager::master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h1 class="page-title">
                <i class="voyager-plus"></i> 国家
            </h1>
            <a href="{{ route('backend.country.index') }}" class="btn-info btn">返回</a>
        </div>

        <div class="container">

            <form class="form-horizontal" method="post" action="{{route('backend.country.update.post')}}">
                {{csrf_field()}}
                <input type="hidden" name="country_id" value="{{$country->country_id}}">
                <div class="form-group">
                    <label >国家名称</label>
                    <input type="text" value="{{$country->country_name}}" class="form-control" name="country_name"  placeholder="国家名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">编辑</button>
            </form>


        </div>



    </div>

@stop