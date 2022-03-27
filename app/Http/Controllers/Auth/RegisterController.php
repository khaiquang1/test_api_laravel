<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Validator;


class RegisterController extends Controller
{
    public function Register(Request $request){
        define('id_root','100000');
        if(isset($_POST["submit"])){
            $validator = Validator::make($request->all(), [ 
                'name' => 'required', 
                'email' => 'bail|required|email', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
                
            ]);
            $id_user = rand(100000,999999);
            // $id_users = User::select('id')->get();

            // var_dump($id_users);
            // foreach($id_users as $value){
            //     if($value == $id_user){
            //         $id_user = rand(100000,999999);
            //     }else{
            //         break;
            //     }
            // }
            if ($validator->fails()) { 
                return redirect('register')->withErrors($validator);       
            }
            $email_user = User::where('email', $request->email)->first();
            if(isset($email_user)){
                return redirect('register')->with('error_email', 'Email already exists');
            }
            $name_user = User::where('name', $request->name)->first();
            if(isset($name_user)){
                return redirect('register')->with('error_user', 'User already exists');
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
                    return redirect('register')->with('error_parent', 'Parent use does not exist');
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
            $user = User::create($input); 
            return view('auth.login'); 
        }
        return view('auth.register');    
    }
    public function Login(Request $request){
        if(isset($_POST["submit"])){
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                $user = Auth::user(); 
                // $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                return view('test.index');
            } 
            else{ 
                return redirect('login')->with('error_login', 'Email or Password do not exists');
            } 
        }
        if (Auth::check()) {
            return view('test.index');
        }else{
            return view('auth.login');
        }  
    }
    public function Logout(){
        Auth::logout();
        return redirect('login');
    }
}
