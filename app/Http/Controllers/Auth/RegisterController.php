<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use App\Models\UserVerification;
use App\Models\UserActivation;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use App\Classes\HandleClasses;
use Validator;
use Carbon\Carbon;
use Mail;



class RegisterController extends Controller
{
    public function Register(Request $request){
        define('id_root','100000');
        if(isset($_POST["submit"])){
            $validator = Validator::make($request->all(), [ 
                'name' => 'required',
                'phone' => 'required',  
                'email' => 'bail|required|email', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
                
            ]);
            $id_user = rand(100000,999999);
            // $check_id = User::where('id',$id_user)->first();

            // var_dump($id_users);
            // while(empty($check_id)){
            //     $id_user = rand(100000,999999);
            // }
            if ($validator->fails()) { 
                return redirect('register')->withErrors($validator);       
            }
            $email_user = User::where('email', $request->email)->first();
            if(isset($email_user)){
                return redirect('register')->with('error_email', 'Email đã tồn tại');
            }
            $name_user = User::where('name', $request->name)->first();
            if(isset($name_user)){
                return redirect('register')->with('error_user', 'User đã tồn tại');
            }

            $input = $request->all(); 
            $input['password'] = bcrypt($input['password']); 
            $input['id'] = $id_user;
            if(empty($request->parent)){
                $input['parent']= id_root;
                $input['tree'] = id_root.",".$id_user;   
                $input['level'] = 2;  
            }else{
                $parent = User::where('id',$request->parent)->first();
                if(empty($parent)){
                    return redirect('register')->with('error_parent', 'Mã giới thiệu không đúng');
                }else{
                    $input['parent']= $request->parent;
                    $input['tree'] = $parent->tree.",".$id_user;
                    switch($parent->level){
                        case 1:
                            $input['level'] = 2;
                            break;
                        case 2:
                            $input['level'] = 3;
                            break;
                        case 3:
                            $input['level'] = 3;
                            break;
                        default:
                            $input['level'] = 2;
                            break;
                    } 
                }
            }
            UserActivation::create([
                "id_user"=>$id_user,
            ]);
            UserVerification::create([
                "id_user"=>$id_user,
                "name_user" =>$request->name_user,
                "phone" =>$request->phone,
                "address" =>$request->address,
            ]);
            $wallet_add = HandleClasses::randomString(16);
            $wallet = Wallet::create([
                "wallet_address" => $wallet_add
            ]);
    
            $input['wallet_id'] = $wallet->id;
            $user = User::create($input); 
            return redirect('login'); 
        }
        if(Auth::check()){
            return redirect('user');
        }else{
            return view('auth.register');    
        }
    }
    public function Login(Request $request){
        if(isset($_POST["submit"])){
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                $user = Auth::user(); 
                if($user->active){
                    return redirect('user');
                }else{
                    return redirect('email/verify');
                }       
            }
            else{ 
                return redirect('login')->with('error_login', 'Email hoặc Password không tồn tại');
            } 
        }
        if (Auth::check()) {
            $user = Auth::user();
            if($user->active){
                return redirect('user');
            }else{
                return redirect('email/verify');
            }       
        }else{
            return view('auth.login');
        }  
    }
    public function Logout(){
        Auth::logout();
        return redirect('login');
    }

    //function send email otp 
    public function verifyEmail(Request $request){
        if(Auth::check() && Auth::user()->block_user){
            $user = Auth::user();

            $user_active = UserActivation::where('id_user', $user->id)->first();
            $user_active->active_code = rand(1000,9999);
            $user_active->active_code_expired_in = Carbon::now()->addSecond(60);
            $user_active->save();
            //send mail
            $to_name= $user->name;
            $to_email = $user->email; 
            $data = array("name"=>$user,"body"=>$user_active->active_code);
            Mail::send('email.user-activation',$data, function($message) use ($to_name,$to_email){
                $message->to($to_email)->subject('Xác thực email');
                $message->from($to_email,$to_name);
            });
            return view('auth.verify-otp', compact('user'));
        }else{
            return redirect('login');
        }
        if(isset($_POST["submit"])){
            $check_code = UserActivation::find($user->id)->where('active_code',$request->active_code)->first();
            if(empty($check_code)){
                return back()->with('active_code','Code không tồn tại');
            }else{
                $user->active = true;
                $user->save();
                return redirect('user');
            }
        }
    }

    public function reVerify(){
        $user = Auth::user();
        UserActivation::find($user->id)->update([
            "active_code" =>rand(1000,9999),
            "active_code_expired_in" =>Carbon::now()->addSecond(60),
        ]);
        //send mail

    }

}
