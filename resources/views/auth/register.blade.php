<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    
    <style>
        body {
            padding-top: 40px;
        }
    </style>
</head>
<body>
    
    <h2 style ="text-align:center; color:blue;">Đăng ký</h2>
    <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            
                            <div class="card-body pt-0"> 
                                
                                <div class="p-2">
                                    <form method="POST" class="form-horizontal mt-4" action="">
                                        @csrf
                                        <div class="form-group">
                                    
                                            <label >Email <i class="obligatory" data-toggle="popover" title="Lưu ý" data-content="Bắt buộc nhập">(*)</i></label>
                                            <input type="email" class="form-control  is-invalid" value="" id="useremail" name="email" required placeholder="Enter email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert" style="color:red;">
                                                    <strong >{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if (session('error_email'))
                                                <span class="invalid-feedback" role="alert" style="color:red;">
                                                    <strong >{{ session('error_email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
    
                                        <div class="form-group">
                                            <label for="username">Username <i data-toggle="popover" title="Lưu ý" data-content="Bắt buộc nhập">(*)</i></label>
                                            <input type="text" class="form-control is-invalid" required name="name" id="username" placeholder="Enter username">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if (session('error_user'))
                                                <span class="invalid-feedback" role="alert" style="color:red;">
                                                    <strong >{{ session('error_user') }}</strong>
                                                </span>
                                            @endif
                                        </div>
    
                                        <div class="form-group">
                                            <label for="userpassword">Password <i data-toggle="popover" title="Lưu ý" data-content="Bắt buộc nhập">(*)</i></label>
                                            <input type="password" class="form-control is-invalid " name="password" required id="userpassword" placeholder="Enter password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert" style="color:red;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label >Password Confirm <i data-toggle="popover" title="Lưu ý" data-content="Bắt buộc nhập">(*)</i></label>
                                            <input id="password-confirm" type="password" name="c_password" class="form-control is-invalid " name="password" required placeholder="Enter password">
                                        </div>
                                            @error('c_password')
                                                <span class="invalid-feedback" role="alert" style="color:red;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        <div class="form-group">
                                            <label >Mã giới thiệu</label>
                                            <input  type="text" name="parent" class="form-control " placeholder="Enter code">
                                        </div>
                                        @if (session('error_parent'))
                                            <span class="invalid-feedback" role="alert" style="color:red;">
                                                <strong >{{ session('error_parent') }}</strong>
                                            </span>
                                        @endif
                                        <h4>Thông tin tài khoản</h4>
                                        <div class="form-group">
                                            <label >Họ và tên </label>
                                            <input  type="text" name="name_user" class="form-control" placeholder="Enter name">
                                        </div>

                                        <div class="form-group">
                                            <label >Số điện thoại <i class="obligatory" data-toggle="popover" title="Lưu ý" data-content="Bắt buộc nhập">(*)</i></label>
                                            <input  type="phone" name="phone" class="form-control is-invalid " placeholder="Enter phone number" required>
                                        </div>

                                        <div class="form-group">
                                            <label >Địa chỉ </label>
                                            <input  type="text" name="address" class="form-control " placeholder="Enter address" >
                                        </div>
                                        
                                        <div class="mt-4">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit" name="submit" >Đăng ký</button>
                                        </div>
    

                                        <div class="mt-4 text-center">
                                            <p class="mb-0">Đồng ý điều khoản thanh toán <a href="#" class="text-primary">Terms of Use</a></p>
                                        </div>
    
                                    </form>
    
                                </div>
                            </div>
    
                        </div>
    
                        <div class="mt-5 text-center">
                            <p>Bạn đã có tài khoản ? <a href="{{route('user.login')}}" class="font-weight-medium text-primary"> Login </a> </p>
                            <p>Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
                        </div>
    
    
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('[data-toggle="popover"]').popover({
                    placement: 'top',
                    trigger: 'hover',
                });
            });
        </script>     
</body>
</html>


