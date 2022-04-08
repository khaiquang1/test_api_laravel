@extends('layout.master')

@section('content')
  
    <div class="container">
        <h2 style ="text-align:center; color:blue;">Ví tiền</h2>
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <h5 class="card-header">Tiền</h5>
                    <div class="card-body">
                        <p class="card-text"><b>Tổng tiền trong ví: </b> {{$wallet->amount}}</p>
                        <p class="card-text"><b>Tổng tiền đầu tư: </b>{{$wallet->invest_money}} </p>
        
                        <button type="button" class="btn btn-xs btn-primary float-right add" id="btnDeposit">Nạp</button>
                        <button type="button" class="btn btn-xs btn-primary float-right add" id="btnWithdraw">Rút</button>
                        <button type="button" class="btn btn-xs btn-primary float-right add" id="btnTransfer">Chuyển</button>
                        @if($user->authenticator == null)
                            <a href="{{route('user.authenticator')}}" class="btn btn-warning">Kích hoạt auth</a>
                        @else
                            <a href="{{route('authenticator.disable')}}" class="btn btn-warning" >Hủy kích hoạt auth</a>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                <strong >{{ session('error') }}</strong>
                            </div>
                        @endif
                       
                    </div>
                    </div>
                </div>
            <div class="col-8">
                <div class="card">
                    <h5 class="card-header">Lịch sử giao dịch</h5>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Loại giao dịch</th>
                                    <th scope="col">Số tiền</th>
                                    <th scope="col">Chi tiết</th>
                                    <th scope="col">Ghi chú</th>
                                    <th scope="col">Thời gian</th>
                         
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deal as $key=>$item)
                                   @if($item->action_type == "Deposit")
                                   <tr>
                                        <td scope="col">{{$key + 1}}</td>
                                        <td>Nạp tiền</td>
                                        <td>{{$item->amount_money}}</td>
                                        <td>Nạp tiền từ STK <b>{{$item->number_bank}}</b> {{$item->name_user_bank}} Ngân hàng <b>{{$item->name}}</b> </td>
                                        <td>{{$item->note}}</td>
                                        <td>{{$item->created_at}}</td>
                                   </tr>    
                                   @elseif($item->action_type == "Withdraw")
                                        <tr>
                                            <td scope="col">{{$key + 1}}</td>
                                            <td>Rút tiền</td>
                                            <td>{{$item->amount_money}}</td>
                                            <td>Rút tiền về STK <b>{{$item->number_bank}}</b> {{$item->name_user_bank}} Ngân hàng <b>{{$item->name}}</b> <b>(Phí: {{$item->fee}})</b></td>
                                            <td>{{$item->note}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @elseif($item->action_type == "Transfer" && $item->id_user_to != $user->id)
                                        <tr>
                                            <td scope="col">{{$key + 1}}</td>
                                            <td>Chuyển tiền</td>
                                            <td>{{$item->amount_money}}</td>
                                            <td>Chuyển tiền qua tài khoản <b>{{$item->id_user_to}}</b></td>
                                            <td>{{$item->note}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr> 
                                    @elseif($item->action_type == "Transfer" && $item->id_user_to == $user->id)
                                    <tr>
                                            <td scope="col">{{$key + 1}}</td>
                                            <td>Nhận tiền</td>
                                            <td>{{- $item->amount_money}}</td>
                                            <td>Nhận tiền từ tài khoản <b>{{$item->id_user}}</b></td>
                                            <td>{{$item->note}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr> 
                                   @endif
                                @endforeach
                            </tbody>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
     <!-- Modal nạp tiền -->
     <div class="modal fade" id="depositModal" role="dialog">
        <div class="modal-dialog">
            
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Nạp tiền</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form class="form" action="{{route('money.deposit')}}" method="POST" id="formModal">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label >Địa chỉ ví của bạn</label>
                            <input type="text" name="wallet_address" class="form-control input-sm" value="{{$wallet->wallet_address}}" readonly>
                        </div>
                        <div class="form-group">
                            <label >Nhập số tiền (Tối thiểu 100 000đ)</label>
                            <input type="number" name="amount_money"  class="form-control input-sm" placeholder="Số tiền" min=0 required>
                        </div>
                        <div class="form-group">
                            <label >Chọn ngân hàng</label>
                            <select name="bank_id" class="form-select" aria-label="Default select example" required>
                                <option selected value="">---Không chọn---</option>
                                @foreach ($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label >Nhập tên chủ thẻ</label>
                            <input type="text" name="name_user_bank" class="form-control input-sm" placeholder="Tên chủ thẻ" required>
                        </div>
                        <div class="form-group">
                            <label >Nhập số thẻ</label>
                            <input type="number" name="number_bank" class="form-control input-sm" placeholder="Số thẻ" required>
                        </div>
                        <div class="form-group">
                            <label >Ghi chú (Nếu có)</label>
                            <textarea name="note" class="form-control" placeholder="Ghi chú"  style="height: 100px"></textarea>
                        </div>
                        <div class="form-group">
                            <label >Nhập mã authenticator</label>
                            <input type="text" name="authen" class="form-control input-sm" placeholder="Mã" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-update" type="submit" name="submit">Nạp</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
                
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="withdrawModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rút tiền</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" action="{{route('money.withdraw')}}" method="POST" id="formModal">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label >Nhập số tiền (Tối thiểu 100000, tối đa {{$wallet->amount - 0.1*$wallet->amount}})</label>
                        <input type="number" name="amount_money" id="withdraw_amount_coin" class="form-control input-sm" min=0 placeholder="Số tiền" required>
                    </div>
                    <div class="form-group">
                        <label >Phí( <span id="fee_amount_withdraw">10</span>% )</label>
                        <input type="text" id="withdraw_amount_fee" name="fee" class="form-control input-sm" readonly placeholder="Phí" >
                    </div>
                    <div class="form-group">
                        <label >Địa chỉ ví của bạn</label>
                        <input type="text" name="wallet_address" class="form-control input-sm" required>
                    </div>
                    <div class="form-group">
                        <label >Chọn ngân hàng</label>
                        <select name="bank_id" class="form-select" aria-label="Default select example" required>
                            <option selected value="">---Không chọn---</option>
                            @foreach ($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label >Nhập tên chủ thẻ</label>
                        <input type="text" name="name_user_bank" class="form-control input-sm" placeholder="Tên chủ thẻ" required>
                    </div>
                    <div class="form-group">
                        <label >Nhập số thẻ</label>
                        <input type="number" name="number_bank" class="form-control input-sm" placeholder="Số thẻ" min=0 required>
                    </div>
                    <div class="form-group">
                        <label >Ghi chú (Nếu có)</label>
                        <textarea name="note" class="form-control" placeholder="Ghi chú"  style="height: 100px"></textarea>
                    </div>
                    <div class="form-group">
                        <label >Nhập mã authenticator</label>
                        <input type="text" name="authen" class="form-control input-sm" placeholder="Mã" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-update" type="submit" name="submit">Rút</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- transfer -->
    <div class="modal" tabindex="-1" role="dialog" id="transferModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chuyển tiền</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" action="{{route('money.transfer')}}" method="POST" id="formModal">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label >Nhập số tiền (Tối thiểu 100 000đ, tối đa {{$wallet->amount}})</label>
                        <input type="number" name="amount_money" class="form-control input-sm" placeholder="Số tiền" min=0 required>
                    </div>
                    <div class="form-group">
                        <label >Chuyển cho ID user</label>
                        <input type="number" name="id_user_to" class="form-control input-sm" placeholder="Nhập ID user" required>
                    </div>
                    <div class="form-group">
                        <label >Ghi chú (Nếu có)</label>
                        <textarea name="note" class="form-control" placeholder="Ghi chú"  style="height: 100px"></textarea>
                    </div>
                    <div class="form-group">
                        <label >Nhập mã authenticator</label>
                        <input type="text" name="authen" class="form-control input-sm" placeholder="Mã" required>
                    </div>
                </div>
                <div class="modal-footer">
                <div class="modal-footer">
                    <button class="btn btn-primary btn-update" type="submit" name="submit">Chuyển</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    


    <script>

        $(document).ready(function(){
            $("#btnDeposit").click(function(){
                $("#depositModal").modal();
            });

            $("#btnWithdraw").click(function(){
                $("#withdrawModal").modal();
            });
            $("#btnTransfer").click(function(){
                $("#transferModal").modal();
            });
        });
        
        $('#withdraw_amount_coin').keyup(function(){
            _amount = parseFloat($(this).val());
            _amount_fee = (_amount*0.1).toFixed(2);
            $('#withdraw_amount_fee').val(_amount_fee);
        });

    </script>
@endsection