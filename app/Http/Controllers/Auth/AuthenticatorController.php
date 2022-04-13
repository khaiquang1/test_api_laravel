<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthenticatorController extends Controller
{
    public function authenUser(Request $request){
        if(Auth::user()->authenticator != null){
            return Redirect::back()->withErrors(['msg'=>'Bạn đã xác thực']);
        }
        $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();

        $secretCode = $googleAuthenticator->createSecret();
        $qrCodeUrl = $googleAuthenticator->getQRCodeGoogleUrl(
            auth()->user()->email, $secretCode, config('app.name')
        );
        session(["secret_code"=>$secretCode]);
        return view('auth.authenticator', compact('qrCodeUrl'));
    }

    public function enableAuthen(Request $request){
        $validator = Validator::make($request->all(), [ 
            'code' => "required|digits:6",
        ]);

        if ($validator->fails()) { 
            return redirect('user/authenticator')->withErrors($validator);       
        }
        $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();

        $secretCode = session("secret_code");
        
        if(!$googleAuthenticator->verifyCode($secretCode, $request->code,0)){
            return redirect('user/authenticator')->with('errors', 'Mã không đúng');
        }

        $user = Auth::user();
        $user->authenticator = $secretCode;
        $user->save();
        return redirect('user/wallet')->with('success', 'Đã kích hoạt google authenticator');
    }

    public function disableAuthen(Request $request){
        if(isset($_POST["submit"])){
            $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();
            $secretCode = Auth::user()->authenticator;
            if(!$googleAuthenticator->verifyCode($secretCode, $request->code,0)){
                return redirect('user/authen/disable')->with('errors', 'Mã không đúng');
            }
            session()->forget('secret_code');
            $user = Auth::user();
            $user->authenticator = null;
            $user->save();
            return redirect('user/wallet')->with('success', 'Đã hủy kích hoạt google authenticator');
        }
        return view('auth.authen-disable');
    }
}
