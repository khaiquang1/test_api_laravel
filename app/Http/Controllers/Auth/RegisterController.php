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
use Illuminate\Support\Facades\Session;
use App\Classes\HandleClasses;
use Carbon\Carbon;
use Mail;



class RegisterController extends Controller
{
    public function Register(Request $request){
        $root = User::where('name', 'root')->first();
        define('id_root',$root->id);
        if(isset($_POST["submit"])){
            $validator = request()->validate([ 
                'name' => 'required',
                'phone' => 'required|min:8',  
                'email' => 'bail|required|email', 
                'password' => 'required||min:6', 
                'c_password' => 'required|same:password', 
                
            ]);

            $id_user = rand(100000,999999);

            // $check = 0;
            // while($check == 0){
            //     
            // }

            // $id_users = User::select('id')->get();
            // foreach ($id_users as $item){
            //     if($item->id == $id_user){
            //         $id_user = rand(100000,999999);
            //         return $id_user;
            //     }
            // }
            
           
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
            $check_wallet = Wallet::where('id_user',$id_user)->first();
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
            UserActivation::create([
                "id_user"=>$id_user,
            ]);
            UserVerification::create([
                "id_user"=>$id_user,
                "name_user" =>$request->name_user,
                "phone" =>$request->phone,
                "address" =>$request->address,
            ]);
            $user = User::create($input); 
            return redirect('login')->with('status_active', 'Bạn đã đăng kí thành công'); 
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
        Session::flush();
        return redirect('login');
    }

    //function send email otp 
    public function verifyEmail(Request $request){
        
        $user = Auth::user();
        if(isset($_POST["submit"])){
            $active_user = UserActivation::where('id_user',$user->id)->first();
            
            if($active_user->active_code == $request->active_code){
                $user->active = true;
                $user->save();
                return redirect('user');
            }else{
                $error ="Mã không đúng";
                return view('auth.verify-otp', compact('error'));
            }
        }

        if(Auth::check() && Auth::user()->block_user ){
            $active_check = UserActivation::where('id_user', $user->id)->first(); 
            if($active_check->active_code == NULL){
                $user_active = UserActivation::where('id_user', $user->id)->first();
                if(empty($user_active)){
                    Auth::logout();
                    return redirect('login');
                }
                $user_active->active_code = rand(1000,9999);
                $user_active->active_code_expired_in = Carbon::now()->addSecond(60);
                $user_active->save();
                //send mail
                $to_name= $user->name;
                $to_email = $user->email; 
                $data = array("name"=>$user->name,"body"=>$user_active->active_code);
                Mail::send('email.user-activation',$data, function($message) use ($to_name,$to_email){
                    $message->to($to_email)->subject('Xác thực email');
                    $message->from($to_email,$to_name);
                });
            }
            return view('auth.verify-otp', compact('user'));
        }else{
            return redirect('login');
        }
       
    }

    public function reVerify(){
        $user = Auth::user();
        if(Auth::check() && Auth::user()->block_user ){
            $user_active = UserActivation::where('id_user', $user->id)->first();
            if(empty($user_active)){
                Auth::logout();
                return redirect('login');
            }
            $user_active->active_code = rand(1000,9999);
            $user_active->active_code_expired_in = Carbon::now()->addSecond(60);
            $user_active->save();
            //send mail
            $to_name= $user->name;
            $to_email = $user->email; 
            $data = array("name"=>$user->name,"body"=>$user_active->active_code);
            Mail::send('email.user-activation',$data, function($message) use ($to_name,$to_email){
                $message->to($to_email)->subject('Xác thực email');
                $message->from($to_email,$to_name);
            });
            return redirect('email/verify')->with('success','Code đã được gửi lại');
        }else{
            return redirect('login');
        }

    }

}
