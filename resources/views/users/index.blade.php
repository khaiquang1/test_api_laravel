<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User</title>

    <!-- Bootstrap CSS -->
 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h2 style ="text-align:center; color:blue;">User</h2>
            <a style="float:right;" href="{{route('user.logout')}}">Logout user</a>
            @if (session('success'))
                <span class="invalid-feedback" role="alert" style="color:red;">
                    <strong >{{ session('success') }}</strong>
                </span>
            @endif
            <div>
                <b>ID: </b><span>{{$user->id}}</span></br>
                <b>E-mail: </b><span>{{$user->email}}</span></br>
            </div>
            <button type="button" class="btn btn-xs btn-primary float-right add" id="btnChangePassword">Đổi mật khẩu</button>
            <button type="button" class="btn btn-xs btn-primary float-right add" id="btnInfoUser">Thông tin</button>
            @if($user->authenticator == null)
                <a href="{{route('user.authenticator')}}" class="btn btn-warning" >Kích hoạt auth</a>
            @else
                <a href="{{route('authenticator.disable')}}" class="btn btn-warning" >Hủy kích hoạt auth</a>
            @endif
            </br>
        
            @if (session('password_status'))
                <span class="invalid-feedback" role="alert" style="color:red;">
                    <strong >{{ session('password_status') }}</strong>
                </span>
            @endif

            @if (session('info_status'))
                <span class="invalid-feedback" role="alert" >
                    <strong >{{ session('info_status') }}</strong>
                </span>
            @endif

            

            <h1>Xác minh</h1>
            @if (session('status_verification'))
                <span class="invalid-feedback" role="alert" >
                    <strong >{{ session('status_verification') }}</strong>
                </span>
            @endif
            <form class="form-horizontal" method="POST" action="/user-verification" enctype="multipart/form-data">
            @csrf
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <label for="formFile" class="form-label">Số CMND/CCCD</label>
                    </div>
                    <input type="text" class="form-control" placeholder="CMND/CCCD" name='number_cmnd' aria-describedby="basic-addon1">
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Ảnh của bạn</label>
                    <input class="form-control" name="image_selfie" type="file" id="formFile">
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Ảnh CMND/CCCD</label>
                    <input class="form-control" name="image_cmnd" type="file" id="formFile">
                </div>
                </br>
                <div class="mt-3" >
                    <button class="btn btn-primary btn-block waves-effect waves-light" type="submit" name="submit">Xác minh</button>
                </div>
            </form>

            <!-- Modal thông tin user -->
             <div class="modal fade" id="informationModal" role="dialog">
                <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thông tin</h4>
                    </div>
                    <form class="form" action="/user-verification/info" method="POST" id="formModal">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Họ và tên</label>
                                <input type="text" name="name_user" class="form-control input-sm" @if(!empty($user_veri)) value='{{$user_veri->name_user}}' @endif>
                            </div>
                            <div class="form-group">
                                <label for="phone">SĐT</label>
                                <input type="number"  name="phone" class="form-control input-sm" @if(!empty($user_veri)) value='{{$user_veri->phone}}' @endif>
                            </div>
                            <div class="form-group">
                                <label for="name">Địa chỉ</label>
                                <input type="text" name="address" class="form-control input-sm" @if(!empty($user_veri)) value='{{$user_veri->address}}' @endif>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary btn-update" type="submit" name="submit">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
                
                </div>
            </div>

            <!-- Modal thay đổi mật khẩu -->
            <div class="modal fade" id="resetPassModal" role="dialog">
                <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thay đổi mật khẩu</h4>
                    </div>
                    <form class="form" action="/reset-password" method="POST" id="formModal">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label >Mật khẩu cũ</label>
                                <input type="password"  name="password_old" class="form-control input-sm" >
                            </div>
                            <div class="form-group">
                                <label >Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label >Nhập lại mật khẩu mới</label>
                                <input type="password" name="c_password" class="form-control input-sm">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary btn-update" type="submit" name="submit">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
                
                </div>
            </div>
            <!--  -->

        </div>
    </div>
</div>

<script>   

    $(document).ready(function(){
        $("#btnInfoUser").click(function(){
            $("#informationModal").modal();
        });

        $("#btnChangePassword").click(function(){
            $("#resetPassModal").modal();
        });
    });
</script>
</body>
</html>


