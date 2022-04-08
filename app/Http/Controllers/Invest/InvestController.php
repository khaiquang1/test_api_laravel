<?php

namespace App\Http\Controllers\Invest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageInvest;
use App\Models\Investment;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class InvestController extends Controller
{
    public function investment(Request $request){
        if(isset($_POST["submit"])){
            $amount = floatval($request->amount_money);
            if($amount < 2000000){
                return redirect('user/investment')->with('error', 'Số tiền đầu tư không hợp lệ');
            }
            $package_check = PackageInvest::where('min_money', '<=', $amount, 'and')->where('max_money', '>', $amount)->first();
            if(empty($package_check)){
                return redirect('user/investment')->with('error', 'Số tiền đầu tư quá lớn');
            }
            $input['id_user'] = Auth::user()->id;
            $input['amount_money'] = $amount;
            $input['package_id'] = $package_check->id;
            Investment::create($input);

            $wallet = Wallet::findOrFail(Auth::user()->wallet_id);
            $wallet->invest_money = floatval($wallet->invest_money + $amount);
            $wallet->save();
            return redirect('user/investment')->with('success','Bạn đã đăng kí gói ')->with('package', $package_check->name);
        }
        $invest = Investment::join('package_invests','investments.package_id','=','package_invests.id')->get();
        return view('invest.investment', compact('invest'));
    }
}
