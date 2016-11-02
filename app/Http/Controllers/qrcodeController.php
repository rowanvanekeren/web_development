<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\lib\QrReader;
use PHPMailer;
//use Validator;
use DateTime;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Winners;
use App\Codes;
use app\Helpers\Helpers;
use Illuminate\Support\Facades\Input;

class qrcodeController extends Controller
{
    public function checkQr(Request $request){

//        if(isset($request->image)){
//            if (true/*$request->file('image')->isValid()*/){
//                $OriginalName = $request->file('image')->getClientOriginalName();
//
//                $request->file('image')->move($destinationPath,$OriginalName);
//
//                $image = base_path() . '/public/images/' . $OriginalName;
//
//                $qrcode = new QrReader($image);
//                $text = $qrcode->text(); //return decoded text from QR Code
//
//               return $text;
//            }
//        }
        if(isset($request->image)){
            try{
                $destinationPath =  base_path() . "/public/images/";
                define('UPLOAD_DIR',$destinationPath);
                $img = $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $unique_code=  "qrcode-" . uniqid() . '.png';
                $file = $destinationPath . $unique_code  ;
                $success = file_put_contents($file, $data);
                $testfile = base_path() . "/public/images/qr.png";
                if($success){
                    try{
                        $qrcode = new QrReader((string)$file);
                        $text = $qrcode->text();
                        if($text != ""){
                            $check = Codes::where('code', $text)->where('user_id', null)->get();
                            if(!$check->isEmpty()){
                                return ['success',$text, $unique_code];
                            }else{
                                return ['fail',"qr bestaat niet in database"];
                            }
                        }
                        else{
                            return ['fail',"qr code niet gevonden"];
                        }
                    }catch(Exception $e){
                        return "qr code niet gevonden";
                    }
                }
                return  "error";
            }catch(Exception $e){
                return "error";
            }
        }
        return "error";
    }
}
