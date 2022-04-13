@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h2 style ="text-align:center; color:blue;">{{trans('user.User')}}</h2>
            
            
            @if (session('success'))
                <span class="invalid-feedback" role="alert" style="color:red;">
                    <strong >{{ session('success') }}</strong>
                </span>
            @endif
            <div>
                <b>ID: </b><span>{{$user->id}}</span></br>
                <b>E-mail: </b><span>{{$user->email}}</span></br>
            </div>
            <button type="button" class="btn btn-xs btn-primary float-right add" id="btnChangePassword">{{trans('user.change-password')}}</button>
            <button type="button" class="btn btn-xs btn-primary float-right add" id="btnInfoUser">{{trans('user.information')}}</button>
            @if($user->authenticator == null)
                <a href="{{route('user.authenticator')}}" class="btn btn-warning" >{{trans('user.active-auth')}}</a>
            @else
                <a href="{{route('authenticator.disable')}}" class="btn btn-warning" >{{trans('user.disable-auth')}}</a>
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

            

            <h1>{{trans('user.verification')}}</h1>
            @if (session('status_verification'))
                <span class="invalid-feedback" role="alert" >
                    <strong >{{ session('status_verification') }}</strong>
                </span>
            @endif
            <form class="form-horizontal" method="POST" action="/user-verification" enctype="multipart/form-data">
            @csrf
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <label for="formFile" class="form-label">{{trans('user.CMND')}}</label>
                    </div>
                    <input type="text" class="form-control" placeholder="CMND/CCCD" name='number_cmnd' aria-describedby="basic-addon1">
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">{{trans('user.your-selfie')}}</label>
                    <input class="form-control" name="image_selfie" type="file" id="formFile">
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">{{trans('user.CMND')}}</label>
                    <input class="form-control" name="image_cmnd" type="file" id="formFile">
                </div>
                </br>
                <div class="mt-3" >
                    <button class="btn btn-primary btn-block waves-effect waves-light" type="submit" name="submit">{{trans('user.verification')}}</button>
                </div>
            </form>

            <!-- Modal thông tin user -->
             <div class="modal fade" id="informationModal" role="dialog">
                <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{trans('user.information')}}</h4>
                    </div>
                    <form class="form" action="/user-verification/info" method="POST" id="formModal">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">{{trans('user.full-name')}}</label>
                                <input type="text" name="name_user" class="form-control input-sm" @if(!empty($user_veri)) value='{{$user_veri->name_user}}' @endif>
                            </div>
                            <div class="form-group">
                                <label for="phone">{{trans('user.phone')}}</label>
                                <input type="number"  name="phone" class="form-control input-sm" @if(!empty($user_veri)) value='{{$user_veri->phone}}' @endif>
                            </div>
                            <div class="form-group">
                                <label for="name">{{trans('user.address')}}</label>
                                <input type="text" name="address" class="form-control input-sm" @if(!empty($user_veri)) value='{{$user_veri->address}}' @endif>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary btn-update" type="submit" name="submit">{{trans('user.save')}}</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('user.close')}}</button>
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
                    <h4 class="modal-title">{{trans('user.change-password')}}</h4>
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
                            <button class="btn btn-primary btn-update" type="submit" name="submit">{{trans('user.save')}}</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('user.close')}}</button>
                        </div>
                    </form>
                </div>
                
                </div>
            </div>
            <!--  -->

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

@endsection

