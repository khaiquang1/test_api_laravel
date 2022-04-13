@extends('layout.master')

@section('content')
<div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2 style ="text-align:center; color:blue;">Danh sách thành viên</h2>
                <a style="float:right;" href="{{route('user.logout')}}">Logout user</a>
                <hr>

                <table class="table">
                <thead>
                    <tr>
                    <th scope="col">STT</th>
                    <th scope="col">ID</th>
                    <th scope="col">Tên người dùng</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Cấp thành viên</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key=>$value )
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$value->id}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{$value->email}}</td>
                            <td>
                                <select name="level" class="form-select" data-id="{{$value->id}}" aria-label="Default select example" {{ $user->level == 1 ? '' : 'disabled' }} >
                                    @foreach ($levels as $item)
                                    <option  value="{{$item->id}}" {{ $item->id == $value->level ? 'selected' : '' }} >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                        <td>{{$value->created_at}}</td>
                        <td>
                            <button id="block-user" data-id="{{$value->id}}" {{ $user->level == 1 ? '' : 'disabled' }} {{$value->block_user == 1 ? "" : "style = background-color:red"}}>Block</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                    
            </div>
        </div>
    </div>
    <script>
        $(".form-select").change(function() {
            var id_user = $(this).data("id");
            var value_level = $(this).val();
            $.ajax({
                    type: "GET",
                    url: "{{url('/user/level')}}"+ "/" + id_user + "/" + value_level,
                    dataType: 'json',
                    success: function(data){
                        if(data.success == true){
                            alert(data.message);
                        }else{
                            alert(data.message);
                        }
                    }
                });
        });

        $('body').on('click', '#block-user', function () {
            var user_id = $(this).data("id");    
            $.ajax({
                type: "GET",
                url: "{{url('/user/block')}}"+ "/" +user_id,
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        alert(data.message);
                        setTimeout(location.reload(),1000);
                    }else{
                        alert(data.message);
                    }
                }
            });
        });
       
    </script>
    <script src="//code.jquery.com/jquery.js"></script> 
@endsection