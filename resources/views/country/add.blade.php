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
            @foreach ($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
            <form class="form-horizontal" method="post" action="{{route('backend.country.add.post')}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label >国家名称</label>
                    <input type="text" class="form-control" name="country_name"  placeholder="国家名称">
                </div>

                <button type="submit" class="btn btn-success pull-right">创建</button>
            </form>


        </div>



    </div>

@stop