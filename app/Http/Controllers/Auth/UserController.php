<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Level;
use App\Models\Wallet;
use Illuminate\Support\Facades\File;
use App\Models\UserVerification;
use App\Classes\HandleClasses;

class UserController extends Controller
{
    public function index(){
        $user= Auth::user();
        $user_veri = UserVerification::where('id_user',$user->id)->first();
        return view('users.index', compact('user_veri','user'));
    }

    public function resetPassword(Request $request){
        $validator = $request->validate ([ 
            'password_old' => 'required|min:6',
            'password' => 'required|min:6', 
            'c_password' => 'required|same:password', 
        ]);
       
        $user = Auth::user();
        if (Hash::check($request->password_old, $user->password)){
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect('user')->with('success', 'Password đã được thay đổi ');
        }else{
            return redirect('user')->with('message', 'Password cũ không tồn tại');
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
        return redirect('user')->with('message', 'Lưu thông tin thành công'); 
    }

    public function verificationUser(Request $request){
        $uploadPath = "upload/users/";
        $validator = $request->validate([ 
            'number_cmnd' => 'required|min:6', 
        ]);
        $check_data = UserVerification::where('id_user', Auth::user()->id)->first();

        //Xử lý hình ảnh cmnd
        if($request->hasFile('image_cmnd')){
            $validator = $request->validate([ 
                'image_cmnd' => 'image|max:10240', 
            ]);
            if($check_data->image_cmnd == null){
                $image_cmnd = HandleClasses::handleImage($request->image_cmnd,$uploadPath);
            }else{
                $image_cmnd = HandleClasses::handleImage($request->image_cmnd,$uploadPath);
                $image_path_cmnd = $uploadPath.$check_data->image_cmnd;  
                if(File::exists($image_path_cmnd)) {
                    File::delete($image_path_cmnd);
                }
            }
        }else{
            if($check_data->image_cmnd == null){
                return redirect('user')->with('error','Hình ảnh không được trống');
            }else{
                $image_cmnd = $check_data->image_cmnd;
            }
        }

        //Xử lý hình ảnh selfie
        if($request->hasFile('image_selfie')){
            $validator = $request->validate([ 
                'image_selfie' => 'image|max:10240', 
            ]);
            if($check_data->image_selfie == null){
                $image_selfie = HandleClasses::handleImage($request->image_selfie,$uploadPath);
            }else{
                $image_selfie = HandleClasses::handleImage($request->image_selfie,$uploadPath);
                $image_path_selfie = $uploadPath.$check_data->image_selfie;  
                if(File::exists($image_path_selfie)) {
                    File::delete($image_path_selfie);
                }
            }
        }else{
            if($check_data->image_selfie == null){
                return redirect('user')->with('error','Hình ảnh không được trống');
            }else{
                $image_selfie = $check_data->image_selfie;
            }
        }
        
        $check_data->number_cmnd = $request->number_cmnd;
        $check_data->image_selfie = $image_selfie;
        $check_data->image_cmnd = $image_cmnd;
        $check_data->save();
        
        return redirect('user')->with('message', 'Thành công');
    }

    public function showAll(Request $request){
        $search = $request->search_user;
        $user = Auth::user();
        $users = User::where('id','<>',null)->orderBy('name','DESC');
        if($search){
            $users = $users->where('id','LIKE', "%{$search}%")
            ->orWhere('name','LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%");
        }
        $search_level = $request->search_level;
        if($search_level){
            $users = $users->where('level',$search_level);
        }
        $users = $users->paginate(15);
        $levels = Level::all();
        return view('users.show-all', compact('users', 'levels', 'user'));   
    }

    public function blockUser($id){
        if(Auth::user()->level == 1){
            $user = User::findOrFail($id);
            if($user->block_user){
                $user->block_user = 0;
                $user->save();
                return ['success' => true, 'message' => 'Chặn thành công'];
            }else{
                $user->block_user = 1;
                $user->save();
                return ['success' => true, 'message' => 'Bỏ chặn thành công'];
            }
            
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

    public function completeUser(Request $request){
        if($request->get('query')){
            $search = $request->get('query');
            $data = User::where('id','LIKE', "%{$search}%")
                        ->orWhere('name','LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->get();
            $output = '<ul class="dropdown-menu list-group" style="display:block; position: relative;">';
            foreach ($data as $row){
                $output .= '<li class="list-group-item">'.$row->email.'</li>';
            }
            $output .= '</ul>';
            echo $output;
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
