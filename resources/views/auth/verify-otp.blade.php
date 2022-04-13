<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <style>
        body {
            padding-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> Verification</div>
                    <a style="float:right;" href="{{route('user.logout')}}">Logout user</a>
                    
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
</body>
</html>
