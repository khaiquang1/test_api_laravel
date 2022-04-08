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
use DB;

class MoneyController extends Controller
{
    public function walletUser(){
        $user = Auth::user();
        $wallet = Wallet::findOrFail($user->wallet_id);
        
        $banks = Bank::all();

        //$deal = Money::where('id_user', $user->id)->orderBy('created_at', 'DESC')->get();
        $deal = DB::table('money')
                ->select('money.*', 'banks.name')
                ->leftJoin('banks','money.bank_id','=','banks.id')
                ->where('id_user',$user->id)
                ->orWhere('id_user_to',$user->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        $deal_user_to = Money::where('id_user_to',$user->id)->first();
        return view('wallet.wallet-user',compact('user','banks','wallet','deal','deal_user_to'));
    }

    

    public function depositMoney(Request $request){
        $validator = Validator::make($request->all(), [
            'amount_money' => 'bail|required|gte:100000',
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
        //nạp tiền vào ví
        $amount_money = floatval($request->amount_money);
        $user = Auth::user();
        $wallet = Wallet::findOrFail($user->wallet_id);
        $wallet->amount = floatval($wallet->amount + $amount_money);
        $wallet->save();
        
        $input = $request->all();
        $input['id_user'] = Auth::user()->id;
        $input['action_type'] = "Deposit";
        Money::create($input);

        return redirect('user/wallet')->with('error', 'Nạp thành công');
    }

    public function withdrawMoney(Request $request){
        $validator = Validator::make($request->all(), [
            'amount_money' => 'bail|required|gte:100000',
            'name_user_bank' => 'required|min:1',
            'authen' => 'required|min:6',
        ]);
        if ($validator->fails()) { 
            return redirect('user/wallet')->withErrors($validator);       
        }
        $wallet = Wallet::where('wallet_address',$request->wallet_address)->first();
        $money_max = floatval($wallet->amount) - floatval($wallet->amount * 0.1);
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
        //rút tiền  ví
        $amount_money = floatval($request->amount_money);
        $user = Auth::user();
        $wallet_check = Wallet::findOrFail($user->wallet_id);
        if($wallet_check->wallet_address != $request->wallet_address){
            return redirect('user/wallet')->with('error', 'Địa chỉ ví không đúng với tài khoản của bạn');
        }
        $wallet->amount = floatval($wallet->amount - $amount_money - $request->fee);
        $wallet->save();


        $input = $request->all();
        $input['id_user'] = Auth::user()->id;
        $input['amount_money'] = - $request->amount_money;
        $input['fee'] = - $request->fee;
        $input['action_type'] = "Withdraw";
        Money::create($input);
        return redirect('user/wallet')->with('error', 'Rút thành công');
    }

    public function transferMoney(Request $request){
        $validator = Validator::make($request->all(), [
            'amount_money' => 'bail|required|gte:100000',
            'id_user_to' => 'required|min:4',
            'authen' => 'required|min:6',
        ]);

        if ($validator->fails()) { 
            return redirect('user/wallet')->withErrors($validator);       
        }

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

        //chuyển tiền
        $user = Auth::user();
        $wallet_from = Wallet::findOrFail($user->wallet_id);
        $wallet_from->amount = floatval($wallet_from->amount) - floatval($request->amount_money);
        $wallet_from->save();

        $wallet_to = Wallet::findOrFail($user_check->wallet_id);
        $wallet_to->amount = floatval($wallet_to->amount) + floatval($request->amount_money);
        $wallet_to->save();

        $input = $request->all();
        $input['amount_money'] = - $request->amount_money;
        $input['id_user'] = Auth::user()->id;
        $input['wallet_address'] = $wallet_from->wallet_address;
        $input['action_type'] = "Transfer";
        Money::create($input);
        return redirect('user/wallet')->with('error', 'Chuyển thành công');
    }
}
