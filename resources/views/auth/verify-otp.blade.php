@extends('layout.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> Verification</div>
                    
                    <div class="card-body">
                        <form method="POST" action="">
                            @csrf
                           
                            <p class="text-center">Chúng tôi đã gửi code vào email của bạn: </p>
                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">Code</label>
    
                                <div class="col-md-6">
                                    <input type="number" class="form-control is-invalid" name="active_code" required autocomplete="code" autofocus min=0>
    
                    
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a class="btn btn-link" href="{{route('email.verify_otp')}}">Gửi lại code?</a>
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" name="submit" class="btn btn-primary btn-update">
                                        Xác nhận
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(isset($error))
                        <div class="invalid-feedback" role="alert" style="color:red;">
                            {{$error}}
                        </div>
                    @endif

                    @if (session('success'))
                        <span class="invalid-feedback" role="alert">
                            <strong >{{ session('success') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
