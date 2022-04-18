@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h2 style ="text-align:center; color:blue;">{{trans('user.User')}}</h2>
            
            <div>
                <b>ID: </b><span>{{$user->id}}</span></br>
                <b>E-mail: </b><span>{{$user->email}}</span></br>
            </div>
            <button type="button" class="btn btn-xs btn-primary add" id="btnChangePassword">{{trans('user.change-password')}}</button>
            <button type="button" class="btn btn-xs btn-primary add" id="btnInfoUser">{{trans('user.information')}}</button>
            @if($user->authenticator == null)
                <a href="{{route('user.authenticator')}}" class="btn btn-warning" >{{trans('user.active-auth')}}</a>
            @else
                <a href="{{route('authenticator.disable')}}" class="btn btn-warning" >{{trans('user.disable-auth')}}</a>
            @endif
            </br>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1>{{trans('user.verification')}}</h1>
            </br>
            <form class="form-horizontal" method="POST" action="/user-verification" enctype="multipart/form-data">
            @csrf
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <label for="formFile" class="form-label">{{trans('user.CMND')}}</label>
                    </div>
                    <input type="text" class="form-control" name='number_cmnd' @if($user_veri->number_cmnd != null) value='{{$user_veri->number_cmnd}}' @else placeholder="{{trans('user.CMND')}}"  @endif >
                </div>
                <style>
                    .img-thumbnail {
                        height:200px;
                    };
                </style>
                <div class="mb-3">
                    <label for="formFile" class="form-label">{{trans('user.your-selfie')}}</label>
                    @if($user_veri->image_selfie != null)
                        <img src="{{asset('upload/users/'.$user_veri->image_selfie)}}" alt="{{$user_veri->name_user}}" class="img-thumbnail">
                        <div class="col">
                            {{trans('user.update-picture')}}
                        </div>
                    @endif
                    <div class="col">
                        <input class="form-control form-control-sm" name="image_selfie" type="file" >
                    </div>   
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">{{trans('user.CMND')}}</label>
                    @if($user_veri->image_cmnd != null)
                        <img src="{{asset('upload/users/'.$user_veri->image_cmnd)}}" alt="{{$user_veri->name_user}}" class="img-thumbnail">
                        <div class="col">
                            {{trans('user.update-picture')}}
                        </div>
                    @endif
                    <div class="col">
                        <input class="form-control form-control-sm" name="image_cmnd" type="file" id="formFile" >
                    </div>
                </div>
                </br>
                <div class="mt-3" >
                    <button class="btn btn-primary btn-block waves-effect waves-light" type="submit" name="submit">
                        {{trans('user.save')}}
                    </button>
                </div>
            </form>

            <!-- Modal thông tin user -->
             <div class="modal fade" id="informationModal" role="dialog">
                <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    
                    <h4 class="modal-title">{{trans('user.information')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                    <h4 class="modal-title">{{trans('user.change-password')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form class="form" action="/reset-password" method="POST" id="formModal">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label >{{trans('user.old-password')}}</label>
                                <input type="password"  name="password_old" class="form-control input-sm" >
                            </div>
                            <div class="form-group">
                                <label >{{trans('user.new-password')}}</label>
                                <input type="password" name="password" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label >{{trans('user.confirm-new-password')}}</label>
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

