<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use Validator;


class RegisterController extends Controller
{
    public function register(Request $request){
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
            $parent = User::where('id',$request->parent)->first();

            if(isset($request->parent)){
                $input['parent']= id_root;
                $input['tree'] = id_root.",".$id_user;   
                $input['level'] = 2;  
            }else{
                if(isset($parent)){
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
                        case 3:
                            $input['level'] = 3;
                        default:
                            $input['level'] = 2;
                    }
                    
                }
            }
            $user = User::create($input); 
            return 'success'; 
        }
        return view('auth.register');    
    }
    public function login(){
        return view('auth.login');
    }
}
