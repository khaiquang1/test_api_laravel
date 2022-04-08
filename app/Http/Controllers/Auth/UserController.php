<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Level;
use App\Models\Wallet;
use App\Models\UserVerification;
use App\Classes\HandleClasses;
use Validator;

class UserController extends Controller
{
    public function index(){
        $user= Auth::user();
        $user_veri = UserVerification::where('id_user',$user->id)->first();
        $id_wallet = $user->wallet_id;
        $wallet = Wallet::findOrFail($id_wallet);
        return view('users.index', compact('user_veri','user','wallet'));
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [ 
            'password_old' => 'required|min:6',
            'password' => 'required|min:6', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return redirect('user')->withErrors($validator);       
        }
        
        $user = Auth::user();
        if (Hash::check($request->password_old, $user->password)){
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect('user')->with('password_status', 'Password đã được thay đổi ');
        }else{
            return redirect('user')->with('password_status', 'Password cũ không tồn tại');
        }

    }

    public function informationUser(Request $request){
        $user = Auth::user();
        UserVerification::where('id_user',$user->id)->update(
            [
                'name_user' =>$request->name_user,
                'phone' =>$request->phone,
                'address' => $request->address,
            ]
        );
        return redirect('user')->with('info_status', 'Lưu thông tin thành công'); 
    }

    public function verificationUser(Request $request){
        $validator = Validator::make($request->all(), [ 
            'number_cmnd' => 'required||min:8',
            'image_selfie' => 'image|max:1024', 
            'image_cmnd' => 'image|max:1024', 
        ]);
        if ($validator->fails()) { 
            return redirect('user')->withErrors($validator);       
        }
        if(empty($request->image_selfie) || empty($request->image_cmnd)){
            return redirect('user')->with('status_verification', 'Hình ảnh trống'); 
        }else{
            $image_selfie = HandleClasses::handleImage($request->image_selfie);
            $image_cmnd = HandleClasses::handleImage($request->image_cmnd);
        }
        $user = Auth::user();
        UserVerification::where('id_user',$user->id)->update(
            [
                'number_cmnd' =>$request->number_cmnd,
                'image_selfie' =>$image_selfie,
                'image_cmnd' => $image_cmnd,
            ]
        );
        return redirect('user')->with('status_verification', 'Xác thực thành công');
    }

    public function showAll(){
        $user = Auth::user();
        $users = User::all();
        $levels = Level::all();
        return view('users.show-all', compact('users', 'levels', 'user'));
        
    }

    public function blockUser($id){
        if(Auth::user()->level == 1){
            $user = User::findOrFail($id);
            if($user->block_user){
                $user->block_user = 0;
                $user->save();
            }else{
                $user->block_user = 1;
                $user->save();
            }
            return ['success' => true, 'message' => 'Chặn thành công'];
        }else{
            return ['error' => true, 'message' => 'Bạn không có quyền'];
        }
       
    }

    public function changeLevelUser($id, $value){
        if(Auth::user()->level == 1){
            $user = User::findOrFail($id);
            $user->level = $value;
            $user->save();
            return ['success' => true, 'message' => 'Thay đổi cấp độ thành công'];
        }else{
            return ['error' => true, 'message' => 'Bạn không có quyền'];
        }
       
    }

    //API
    // public $successStatus = 200;
    // public function login(){
    //     if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
    //         $user = Auth::user(); 
    //         $success['token'] =  $user->createToken('MyApp')-> accessToken; 
    //         return response()->json(['success' => $success], $this-> successStatus); 
    //     } 
    //     else{ 
    //         return response()->json(['error'=>'error'], 401); 
    //     } 
    // }

    // public function register(){
    //     $validator = Validator::make($request->all(), [ 
    //         'name' => 'required', 
    //         'email' => 'required|email', 
    //         'password' => 'required', 
    //         'c_password' => 'required|same:password', 
    //     ]);
    //     if ($validator->fails()) { 
    //                 return response()->json(['error'=>$validator->errors()], 401);            
    //             }
    //     $input = $request->all(); 
    //             $input['password'] = bcrypt($input['password']); 
    //             $user = User::create($input); 
    //             $success['token'] =  $user->createToken('MyApp')-> accessToken; 
    //             $success['name'] =  $user->name;
    //     return response()->json(['success'=>$success], $this-> successStatus); 
    // }

    // public function details(){
    //     $user = Auth::user(); 
    //     return response()->json(['success' => $user], $this-> successStatus); 
    // }


}
