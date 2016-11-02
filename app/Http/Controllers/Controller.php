<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\lib\QrReader;
use PHPMailer;
//use Validator;
use DateTime;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Winners;
use App\Codes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use app\Helpers\Helpers;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function checkIP(){

        $ip = new Helpers;
        $ip->getIP();
        $ipCheck = User::where('ip_adres', $ip)->get();

        if(!$ipCheck->isEmpty()){

            $ipExist = true;
            return $ipExist;
        }else{
            $ipExist = false;
            return $ipExist;
        }

    }

    public function getHome(){

        $ipExist = $this->checkIP();
        return view('welcome',['ipExist' => $ipExist]);

    }

    public function getUploadPage(){
        $ipExist = $this->checkIP();

        if($ipExist){
            return view('welcome',['ipExist' => $ipExist]);
        }else{
            return view('uploadcode');
        }



    }

    public function saveCode($code,$userID){

        $checkusr = Codes::where('user_id', $userID)->get();
        $checkusedcode = Codes::where('code', $code)->where('user_id', null)->get();

        if($checkusr->isEmpty()){
            if(!$checkusedcode->isEmpty()){
                $getCode = Codes::where('code', $code)->first();

                    $getCode->user_id = $userID;
                    $getCode->save();

                return "succes";

            }else{
                return "there already is this code with a user";
            }
        }else{
            return "there already is this code with a user";
        }

    }



    public function runFakeDateOnce(){
        $length = 8;

        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

        return $randomString;
    }

    public function addcodes(){
        for($i = 0; $i < 40; $i++){
            $newcode = $this->runFakeDateOnce();
            $code = new Codes([
                "code" => $newcode
            ]);

            $code->save();
        }
    }

    public function submit_user(Request $request){
       $ipExist = $this->checkIP();
        if($ipExist){
            return view('welcome',['ipExist' => $ipExist]);
        }else{
            return view('/userinformation',["successorfail" => $request->successorfail,
                "user_image"=> $request->user_image,
                "user_code" => $request->user_code ]);
        }

    }

    public function submit_participation(Request $request){
        $ip = $this->getIP();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'adres' => 'required',
            'housenumber' => 'required',
            'city' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('userinformation')
                ->withErrors($validator)
                ->withInput();
        }else{
            $user = new User([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'adres' => $request->adres,
                'housenumber' => $request->housenumber,
                'city' => $request->city,
                'ip_adres' => $ip,
                'image' => $request->image,
                'active' => 1,
            ]);

            $user->save();
            $code = $this->saveCode($request->code,$user->id);
            $mail = new Helpers;
            $mail->sendConfirmMail($user->email,$user->first_name);
/*            $mail = $this->sendConfirmMail($user->email,$user->first_name);*/

            return view('confirm',['hasSubmitted' => true ]);
        }


        //$this->sendConfirmMail();
    }



    public function getWinners(){

        $winners = User::has('winner')->get();

        return view('winners',['winners' => $winners]);
    }

    public function getAdmin(){
        $users = User::all();
        return view('admin',['users' => $users]);
    }

    public function delete_users(Request $request){
        $selectedUsers = $request->selectedUsers;
        $users = User::all();
        if(isset($selectedUsers)){
            $active = 1;
            if(isset($request->delete)){
        $active = 0;
            }else if(isset($request->add)){
                $active = 1;
            }else if(isset($request->hard_delete)){
                foreach ($selectedUsers as $user) {
                    $delete = User::where('id',$user)->first();
                    $code = Codes::where('user_id',$user)->first();
                    $winner = Winners::where('user_id',$user)->first();
                    if($code != null){
                        $code->user_id = null;
                        $code->save();
                    }
                    if($winner != null){
                        $winner->delete();
                    }

                    $delete->delete();
                }
                return $this->getAdmin();
            }
            foreach ($selectedUsers as $user) {
                $update = User::where('id',$user)->first();
                $update->active = $active;
                $update->save();
            }
            return $this->getAdmin();
        }
        else{
            return $this->getAdmin();
        }


    }
}
