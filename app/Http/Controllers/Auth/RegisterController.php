<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request){
        $levels = Level::all();
        if(isset($_POST["submit"])){
            $validator = Validator::make($request->all(), [ 
                'name' => 'required', 
                'email' => 'required|email', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
                
            ]);
            $id_user = Str::random(16) ;
            $mess_fail = 'create error';
            if ($validator->fails()) { 
                return $mess_fail;            
            }
            $input = $request->all(); 
            $input['password'] = bcrypt($input['password']); 
            $parentRoot = User::find(1);
            $parent = User::where('id_user',$request->parents)->first();
            
            if(isset($request->parents)){
                if(isset($parent)){
                    $input['parents']= $request->parents;
                    $input['tree'] = $parent->tree.",".$id_user;
                }else{
                    return "!!!!!parent";
                }
            }else{
                $input['parents']= $parentRoot->id_user;
                $input['tree'] = $parentRoot->id_user.",".$id_user;
            }
            
            $input['id_user'] = $id_user ;
            $user = User::create($input); 
            return 'success'; 
        }
        return view('auth.register', compact("levels"));    
    }
    public function login(){
        return view('auth.login');
    }
}
