@extends('layout.master')

@section('content')
  
<div class="container">
    <h2 style ="text-align:center; color:blue;">Đầu tư</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Hợp tác</h5>
                @if (session('error'))
                    <span class="invalid-feedback" role="alert" style="color:red;">
                        <strong >{{ session('error') }}</strong>
                    </span>
                @endif
                @if (session('success'))
                    <span class="invalid-feedback" role="alert" style="color:red;">
                        <strong >{{ session('success')}}{{session('package')}}</strong>
                    </span>
                @endif
                <p class="card-text">
                <form class="form" action="{{route('money.invest')}}" method="POST" >
                @csrf
                    <div class="form-group">
                        <label >Nhập số tiền hợp tác (Tối thiểu 2 000 000đ)</label>
                        <input type="number" name="amount_money"  class="form-control input-sm" placeholder="Số tiền" min=0 id="amount" required>
                    </div>
                    </br>
                    <div class="form-group">
                        <button class="btn btn-primary btn-update" type="submit" name="submit">Hợp tác</button>
                    </div>
                </form>
                </p>
            </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dữ liệu gói</h5>
                <p class="card-text">
                    <div class="row">
                        <div class="col">
                        Số tiền: 
                        </div>
                        <div class="col" id="amount_money">
                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                        Lãi mỗi ngày: 
                        </div>
                        <div class="col" id="interest_day">
                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                        Tổng lãi 2 năm: 
                        </div>
                        <div class="col" id="interest_2year">
                        
                        </div>
                    </div>

                </p>
            </div>
            </div>
        </div>

        <div>
            <div class="card">
                <h5 class="card-header">Lịch sử đầu tư</h5>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Số tiền</th>
                                <th scope="col">Gói</th>
                                <th scope="col">Lãi ngày</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invest as $key=>$value)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$value->amount_money}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{($value->amount_money * $value->percent_interest_day)/100}} VND</td>
                                <td>{{$value->created_at}}</td>
                                <td>
                                    @if($value->status == 0)
                                        đang chờ
                                    @else
                                        hoàn thành
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function(){
            _percent_gold_package1 = parseFloat(0.05);
            _percent_gold_package2 = parseFloat(0.1);
            _percent_gold_package3 = parseFloat(0.5);
            $('#amount').on('input',function(e){
                _amount = parseFloat($(this).val());
                if(_amount >= 2000000 && _amount < 50000000){
                    _percent_day = (_percent_gold_package1 * _amount)/100;
                    _percent_2year = _percent_day * 720;
                }else if(_amount>= 50000000 && _amount < 100000000){
                    _percent_day = (_percent_gold_package2 * _amount)/100;
                    _percent_2year = _percent_day * 720;
                }else if(_amount >= 100000000 ){
                    _percent_day = (_percent_gold_package3 * _amount)/100;
                    _percent_2year = _percent_day * 720;
                }else if(_amount < 2000000){
                    _percent_day = 0;
                    _percent_2year = 0;
                }
                $('#amount_money').html(_amount.toFixed(2) + ' VND');
                $('#interest_day').html(_percent_day.toFixed(2) + ' VND');
                $('#interest_2year').html(_percent_2year.toFixed(2) + ' VND');
            });
        });
    </script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> -->
@endsection