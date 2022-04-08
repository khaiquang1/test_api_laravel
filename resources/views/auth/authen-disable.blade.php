@extends('layout.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
                <div class="font-weight">Xác thực Google Authenticator</div>
                @if (session('msg'))
                    <span class="invalid-feedback" role="alert" style="color:red;">
                        <strong >{{ session('msg') }}</strong>
                    </span>
                @endif
                <div class="card-body">
                    <form role="form" method="post" action="{{route('authenticator.disable')}}">
                        {{ csrf_field() }}
                        <h5>Nhập code 6 chữ số</h5>
                        
                        <div class="form-group">
                            <input type="text" name="code" class="form-control" placeholder="123456">
                        </div>
                        @if (session('errors'))
                            <span class="invalid-feedback" role="alert" style="color:red;">
                                <strong >{{ session('errors') }}</strong>
                            </span>
                        @endif
                        <div class="form-group">
                            <button class="btn btn-success" type="submit" name="submit">Xác nhận</button>
                            <a href="{{route('user')}}" class="btn btn-secondary float-right">Hủy</a>
                        </div>
                    </form>
                </div>
            
        </div>
    </div>
</div>
@endsection