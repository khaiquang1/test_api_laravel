<?php

namespace App\Http\Controllers\Money;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Classes\HandleClasses;
use App\Models\Bank;
use App\Models\Wallet;
use App\Models\Money;
use App\Models\User;
use App\Models\Currency;
use DB;

class MoneyController extends Controller
{
    public function walletUser(){
        $user = Auth::user();
        $check_wallet = Wallet::where('id_user',$user->id)->first();
        if(empty($check_wallet)){
            Wallet::create([
                'id_user' => $user->id,
                'wallet_address' => HandleClasses::randomString(16),
                'type_money' => "VND",
            ]);

            Wallet::create([
                'id_user' => $user->id,
                'wallet_address' => HandleClasses::randomString(16),
                'type_money' => "USD",
            ]);
        }
        $user = Auth::user();
        $banks = Bank::all();
        $currency = Currency::all();
        $wallet_usd = Wallet::where([
                    ['id_user','=',$user->id],
                    ['type_money','USD'],
                ])->first();
        $wallet_vnd =  Wallet::where([
            ['id_user','=',$user->id],
            ['type_money','VND'],
        ])->first();
        //$deal = Money::where('id_user', $user->id)->orderBy('created_at', 'DESC')->get();
        $deal = DB::table('money')
                ->select('money.*', 'banks.name')
                ->leftJoin('banks','money.bank_id','=','banks.id')
                ->where('id_user',$user->id)
                // ->orWhere('id_user_to',$user->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        $deal_user_to = Money::where('id_user_to',$user->id)->first();
        return view('wallet.wallet-user',compact('user','banks','deal','deal_user_to','wallet_usd','wallet_vnd','currency'));
    }

    

    public function depositMoney(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'amount_money' => 'bail|required|gte:0',
            'name_user_bank' => 'required|min:1',
            'authen' => 'required|min:6',
        ]);
        if ($validator->fails()) { 
            return redirect('user/wallet')->withErrors($validator);       
        }
        if(Auth::user()->authenticator == null){
            return redirect('user/wallet')->with("error", "Bạn cần bật authenticator !!");
        }else{
            $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();
            $secretCode = Auth::user()->authenticator;
            if(!$googleAuthenticator->verifyCode($secretCode, $request->authen,0)){
                return redirect('user/wallet')->with('error', 'Mã Authenticator không đúng !!');
            }
        }

        $check_currency = Currency::findOrFail($request->currency_id);
        $name_bank = Bank::findOrFail($request->bank_id);
        $amount_money = floatval($request->amount_money) * $check_currency->rate;
        //thêm dữ liệu vào money
        $input = $request->all();
        if($check_currency->output_money_type == 'USD'){
            $wallet_usd = Wallet::where([
                ['id_user','=',$user->id],
                ['type_money','USD'],
            ])->first();
            $input['detail'] = "Nạp ".$amount_money."USD từ tài khoản ".$request->number_bank." ".$request->name_user_bank." Ngân hàng ".$name_bank->name;
            $input['wallet_address'] = $wallet_usd->wallet_address;
            //Cộng tiền vào tài khoản
            $wallet_usd->amount = $wallet_usd->amount + $amount_money;
            $wallet_usd->save();
        }elseif($check_currency->output_money_type == 'VND'){
            $wallet_vnd =  Wallet::where([
                ['id_user','=',$user->id],
                ['type_money','VND'],
            ])->first();
            $input['detail'] = "Nạp ".$amount_money."VND từ tài khoản ".$request->number_bank." ".$request->name_user_bank." Ngân hàng ".$name_bank->name;
            $input['wallet_address'] = $wallet_vnd->wallet_address;
            //Cộng tiền vào tài khoản
            $wallet_vnd->amount = $wallet_vnd->amount + $amount_money;
            $wallet_vnd->save();
           
        }else{
            return redirect('user/wallet')->with('error','Lỗi không xác định');
        }
        $input['amount_money'] = $amount_money;
        $input['rate'] = $check_currency->rate;
        $input['id_user'] = Auth::user()->id;
        $input['action_type'] = "Nạp tiền";
        Money::create($input);
        return redirect('user/wallet')->with('success', 'Nạp thành công');
    }

    public function withdrawMoney(Request $request){
        $check_wallet = Wallet::where('wallet_address',$request->wallet_address)->first();
        if(empty($check_wallet)){
            return redirect('user/wallet')->with('error', 'Địa chỉ ví không đúng');
        }else{
            $money_max = floatval($check_wallet->amount) - floatval($check_wallet->amount * 0.1);
            if($request->amount_money > floatval($money_max)){
                return redirect('user/wallet')->with("error", "Số tiền rút không hợp lệ");
            }
            if(Auth::user()->authenticator == null){
                return redirect('user/wallet')->with("error", "Bạn cần bật authenticator !!");
            }else{
                $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();
                $secretCode = Auth::user()->authenticator;
                if(!$googleAuthenticator->verifyCode($secretCode, $request->authen,0)){
                    return redirect('user/wallet')->with('error', 'Mã Authenticator không đúng !!');
                }
            }

            if($check_wallet->type_money == 'USD'){
                $validator = Validator::make($request->all(), [
                    'amount_money' => 'bail|required|gte:5',
                    'name_user_bank' => 'required|min:1',
                    'authen' => 'required|min:6',
                ]);
                if ($validator->fails()) { 
                    return redirect('user/wallet')->withErrors($validator);       
                }

                //thêm dữ liệu vào bảng money
                $input = $request->all();
                $input['id_user'] = Auth::user()->id;
                $input['amount_money'] = - $request->amount_money;
                $input['currency_id'] = 2;
                $input['rate'] = 1;
                $input['fee'] = - $request->fee;
                $input['action_type'] = "Rút tiền";

                $name_bank = Bank::findOrFail($request->bank_id);
                $input['detail'] = "Rút ".$request->amount_money."USD vào tài khoản ".$request->number_bank." ".$request->name_user_bank." Ngân hàng ".$name_bank->name." (Phí ".$request->fee."USD)";
                Money::create($input);

                //Trừ tiền Ví USD
                $check_wallet->amount = floatval($check_wallet->amount - $request->amount_money - $request->fee);
                $check_wallet->save();
                return redirect('user/wallet')->with('success', 'Rút thành công');

            }elseif($check_wallet->type_money == 'VND'){
                $validator = Validator::make($request->all(), [
                    'amount_money' => 'bail|required|gte:100000',
                    'name_user_bank' => 'required|min:1',
                    'authen' => 'required|min:6',
                ]);
                if ($validator->fails()) { 
                    return redirect('user/wallet')->withErrors($validator);       
                }
                
                //thêm dữ liệu vào bảng money
                $input = $request->all();
                $input['id_user'] = Auth::user()->id;
                $input['amount_money'] = - $request->amount_money;
                $input['currency_id'] = 3;
                $input['rate'] = 1;
                $input['fee'] = - $request->fee;
                $input['action_type'] = "Rút tiền";

                $name_bank = Bank::findOrFail($request->bank_id);
                $input['detail'] = "Rút ".$request->amount_money."VND vào tài khoản ".$request->number_bank." ".$request->name_user_bank." Ngân hàng ".$name_bank->name." (Phí ".$request->fee."VND)";
                Money::create($input);

               //Trừ tiền Ví USD
               $check_wallet->amount = floatval($check_wallet->amount - $request->amount_money - $request->fee);
               $check_wallet->save();
                return redirect('user/wallet')->with('success', 'Rút thành công');

            }else{
                return redirect('user/wallet')->with('error', 'Lỗi không xác định');
            }
        }
    }

    public function transferMoney(Request $request){
        $user_check = User::where('id',$request->id_user_to)->first();
        if(empty($user_check)){
            return redirect('user/wallet')->with("error", "ID user cần chuyển không tồn tại !!");
        }
        if(Auth::user()->authenticator == null){
            return redirect('user/wallet')->with("error", "Bạn cần bật authenticator !!");
        }else{
            $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();
            $secretCode = Auth::user()->authenticator;
            if(!$googleAuthenticator->verifyCode($secretCode, $request->authen,0)){
                return redirect('user/wallet')->with('error', 'Mã Authenticator không đúng !!');
            }
        }

        $check_wallet = Wallet::where('wallet_address',$request->wallet_address)->first();
        if(empty($check_wallet)){
            return redirect('user/wallet')->with('error', 'Địa chỉ ví không đúng');
        }else{
            if($check_wallet->type_money == "USD"){
                $validator = Validator::make($request->all(), [
                    'amount_money' => 'bail|required|gte:5',
                    'id_user_to' => 'required|min:4',
                    'authen' => 'required|min:6',
                ]);
        
                if ($validator->fails()) { 
                    return redirect('user/wallet')->withErrors($validator);       
                }
                //chuyển tiền
                $wallet_from = Wallet::where('wallet_address',$request->wallet_address)->first();
                $wallet_from->amount = floatval($wallet_from->amount) - floatval($request->amount_money);
                $wallet_from->save();

                //Nhận tiền
                $wallet_to = Wallet::where([
                    ['id_user','=',$request->id_user_to],
                    ['type_money','=','USD'],
                ])->first();
                $wallet_to->amount = floatval($wallet_to->amount) + floatval($request->amount_money);
                $wallet_to->save();

                // thêm dữ liệu vào money, chuyển tiền
                $input = $request->all();
                $input['amount_money'] = - $request->amount_money;
                $input['id_user'] = Auth::user()->id;
                $input['action_type'] = "Chuyển tiền";
                $input['detail'] = "Chuyển ".$request->amount_money."USD cho tài khoản ".$request->id_user_to;
                $input['currency_id'] = 2;
                $input['rate'] = 1;
                Money::create($input);
                // thêm dữ liệu vào money, nhận tiền
                $input_to = $request->all();
                $input_to['id_user'] = $request->id_user_to;
                $input_to['action_type'] = "Nhận tiền";
                $input_to['wallet_address'] = $wallet_to->wallet_address;
                $input_to['detail'] = "Nhận ".$request->amount_money."USD từ tài khoản ".Auth::user()->id;
                $input_to['currency_id'] = 2;
                $input_to['rate'] = 1;
                Money::create($input_to);
                return redirect('user/wallet')->with('success', 'Chuyển tiền thành công');
            }elseif($check_wallet->type_money == "VND"){
                $validator = Validator::make($request->all(), [
                    'amount_money' => 'bail|required|gte:100000',
                    'id_user_to' => 'required|min:4',
                    'authen' => 'required|min:6',
                ]);
        
                if ($validator->fails()) { 
                    return redirect('user/wallet')->withErrors($validator);       
                }

                //chuyển tiền
                $wallet_from = Wallet::where('wallet_address',$request->wallet_address)->first();
                $wallet_from->amount = floatval($wallet_from->amount) - floatval($request->amount_money);
                $wallet_from->save();

                //Nhận tiền
                $wallet_to = Wallet::where([
                    ['id_user','=',$request->id_user_to],
                    ['type_money','=','VND'],
                ])->first();
                $wallet_to->amount = floatval($wallet_to->amount) + floatval($request->amount_money);
                $wallet_to->save();

                // thêm dữ liệu vào money, chuyển tiền
                $input = $request->all();
                $input['amount_money'] = - $request->amount_money;
                $input['id_user'] = Auth::user()->id;
                $input['action_type'] = "Chuyển tiền";
                $input['detail'] = "Chuyển ".$request->amount_money."VND cho tài khoản ".$request->id_user_to;
                $input['currency_id'] = 3;
                $input['rate'] = 1;
                Money::create($input);
                // thêm dữ liệu vào money, nhận tiền
                $input_to = $request->all();
                $input_to['id_user'] = $request->id_user_to;
                $input_to['action_type'] = "Nhận tiền";
                $input_to['wallet_address'] = $wallet_to->wallet_address;
                $input_to['detail'] = "Nhận ".$request->amount_money."VND từ tài khoản ".Auth::user()->id;
                $input_to['currency_id'] = 3;
                $input_to['rate'] = 1;
                Money::create($input_to);
                return redirect('user/wallet')->with('success', 'Chuyển tiền thành công');

            }else{
                return redirect('user/wallet')->with('error', 'Lỗi không xác định');
            }
        }
    }
 
    

}
