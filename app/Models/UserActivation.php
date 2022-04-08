<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user','active_code','active_code_expired_in',
    ];

    protected $table = 'user_activations';

    protected function getToken(){
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function createActivation($user){
        $activation = $this->getActivation($user);
        if(!$activation){
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);
    }

    private function regenerateToken($user){
        $token = $this->getToken();
        UserActivation::where('id_user', $user->id)->update([
            'token' => $token,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    private function createToken($user)
    {
        $token = $this->getToken();
        UserActivation::insert([
            'id_user' => $user->id,
            'token' => $token,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    private function getActivation($user){
        return UserActivation::where('id_user', $user->id)->first();
    }

    public function getActivationByToken($token)
    {
        return UserActivation::where('token', $token)->first();
    }

    public function deleteActivation($token){
        UserActivation::where('token',$token)->delete();
    }

}
