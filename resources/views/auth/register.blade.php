<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <style>
        body {
            padding-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2 style ="text-align:center; color:blue;">Register</h2>
            </div>
        </div>
    </div>
    <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            
                            <div class="card-body pt-0"> 
                                <div>
                                    <a href="">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="assets/images/logo.svg" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form method="POST" class="form-horizontal mt-4" action="">
                                        @csrf
                                        <div class="form-group">
                                            <label >Email</label>
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
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" value="" required name="name" id="username" placeholder="Enter username">
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
                                            <label for="userpassword">Password</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required id="userpassword" placeholder="Enter password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert" style="color:red;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label >Password Confirm</label>
                                            <input id="password-confirm" type="password" name="c_password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter password">
                                        </div>
                                            @error('c_password')
                                                <span class="invalid-feedback" role="alert" style="color:red;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        <div class="form-group">
                                            <label >Parent User</label>
                                            <input  type="text" name="parent" class="form-control is-invalid " placeholder="Enter code">
                                        </div>
                                       
                                        
                                        <div class="mt-4">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit" name="submit" >Register</button>
                                        </div>
    

                                        <div class="mt-4 text-center">
                                            <p class="mb-0">By registering you agree to the Me <a href="#" class="text-primary">Terms of Use</a></p>
                                        </div>
    
                                    </form>
    
                                </div>
                            </div>
    
                        </div>
    
                        <div class="mt-5 text-center">
                            <p>Already have an account ? <a href="{{route('user.login')}}" class="font-weight-medium text-primary"> Login </a> </p>
                            <p>Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
                        </div>
    
    
                    </div>
                </div>
            </div>
        </div>

               
</body>
</html>


