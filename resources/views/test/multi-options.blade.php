@extends('layout.master')

@section('content')
    <h2 style ="text-align:center; color:blue;">{{trans('user.Test-multi')}}</h2> 
    @foreach ($lists as $key=>$value)
        <div>
            {{$value->title}}
            @foreach ($value->children as $item)
                <div style="margin-left:30px">
                    {{$item->title}}
                </div>
            @endforeach
        </div>
    @endforeach
@endsection