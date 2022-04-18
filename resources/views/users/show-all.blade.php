@extends('layout.master')

@section('content')
<div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2 style ="text-align:center; color:blue;">Danh sách thành viên</h2>
               
                <hr>
                <div class="container">
                    <div class="row">
                        <div class="col-sm">
                            <form class="form" method="GET">
                                @csrf
                                 <div class="input-group">
                                    <input type="text" name="search_user" class="form-control" placeholder="Tìm kiếm..." id="searchUser" required  />
                                    <button type="submit" name="submit" class="btn btn-primary">Tìm kiếm</button>
                                </div>
                                <div class="input-group" id='completeUser'></div>
                            </form>
                            @if(isset($count_result))
                                {{$count_result}}
                            @endif
                        </div>
                        <div class="col-sm">   
                            <form class="form" method="GET" >
                            @csrf
                                <div class="input-group">  
                                    <select class="form-control" name="search_level" aria-label="Default select example" required>
                                        <option value="" selected>Cấp thành viên</option>
                                        @foreach($levels as $level)
                                            <option value="{{$level->id}}">{{$level->name}}</option>
                                        @endforeach
                                    </select>                       
                                    <button class="btn btn-primary">Tìm kiếm</button>
                                </div>  
                            </form>
                        </div>
                        <div class="col-sm">
                       
                        </div>
                    </div>
                </div>
                <div class="row font-italic float-right">{{$users->total()}} results</div>
                <table class="table" id="userTable">
                <thead>
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">ID</th>
                        <th scope="col">Tên người dùng</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Cấp thành viên</th>
                        <th scope="col">Kích hoạt</th>
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
                        <td>
                            @if($value->active == false)
                                No
                            @else
                                Yes
                            @endif
                        </td>
                        <td>{{$value->created_at}}</td>
                        <td>
                            <button id="block-user" data-id="{{$value->id}}" {{ $user->level == 1 ? '' : 'disabled' }} {{$value->block_user == 1 ? "" : "style = background-color:red"}}>Block</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table> 
               
                @if($users->lastPage() > 1)
                    <nav aria-label="Page navigation example">
                    <ul class="pagination d-flex justify-content-end">
                        <li class="page-item">
                            <li class="btn btn-light" >
                                <a style="text-decoration:none" class="{{($users->currentPage() == 1) ? 'disabled' : '' }}" href="{{ $users->url(1) }}">First</a>
                            </li>
                            <a class="page-link {{($users->currentPage() == 1) ? 'disabled' : '' }}" href="{{$users->url($users->currentPage() - 1)}}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @for($i=1; $i <= $users->lastPage(); $i++)
                        <?php
                            $link_limit = 7;
                            $half_total_links = floor($link_limit/2);
                            $from = $users->currentPage() - $half_total_links;
                            $to = $users->currentPage() + $half_total_links;
                            if($users->currentPage() < $half_total_links){
                                $to += $half_total_links - $users->currentPage();
                            }
                            if($users->lastPage() - $users->currentPage() < $half_total_links){
                                $from -= $half_total_links - ($users->lastPage() - $users->currentPage()) - 1;
                            }
                        ?>
                            @if($from < $i && $i < $to)
                                <li class="{{$users->currentPage() == $i ? 'active' : ''}}">
                                    <a class="page-link" href="{{$users->url($i)}}">{{$i}}</a>
                                </li>
                            @endif
                        @endfor
                        
                        
                        <li class="page-item">
                            <a class="page-link {{ ($users->currentPage() == $users->lastPage()) ? ' disabled' : '' }}" href="{{$users->url($users->currentPage() + 1)}}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                            <li class="btn btn-light">
                                <a style="text-decoration:none" class="{{ ($users->currentPage() == $users->lastPage()) ? ' disabled' : '' }}" href="{{ $users->url($users->lastPage()) }}">Last</a>
                            </li>
                        </li>
                    </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
    <style>
        a.disabled {
            pointer-events: none;
        }
        li.active a{
            background-color: #86afed;
        }
    </style>

    <script>
        $(document).ready(function(){
            $('#searchUser').keyup(function() {
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{url('user/complete')}}",
                        type: "POST",
                        data:{query:query, _token:_token},
                        success: function(data){
                            
                            $('#completeUser').fadeIn();
                            $('#completeUser').html(data);

                        },
                        error:function(data){
                            console.log('Error')
                        },
                    });
                };
            });

            $(document).on('click','li', function(){
                $('#searchUser').val($(this).text());
                $('#completeUser').fadeOut();
            });
        });

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
@endsection