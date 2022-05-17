<?php

namespace App\Classes;

class HandleClasses {
    public function handleImage($file,$uploadPath){
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = time()."_".md5(rand(0,999)).".".$fileExtension;
        $file->move($uploadPath, $fileName);
        return $fileName;
    }

    public function randomString($strength = 16){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = strlen($permitted_chars);
        $random_string ='';
        for($i = 0; $i < $strength; $i++){
            $rand_char = $permitted_chars[rand(0,$length-1)];
            $random_string .= $rand_char;
        }
        return $random_string;
    }
    
    public function formatVND($money){
        return number_format($money,2,',',' ')."đ";
    }
}