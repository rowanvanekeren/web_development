<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class CronController extends Controller
{
    public function pickWinner(){

        $adminMail = "rowanvanekeren@hotmail.com";
        $adminTitle = "rowan van ekeren";

        $date = new DateTime();
        $currentFormat = $date->format('Y-m-d H:i:s');
        $year = $date->format('Y');
        $month = $date->format('m');
        $periodmonth1 = $year. "-01-01";
        $period1end = $year. "-03-31";
        $periodmonth2 = $year. "-04-01";
        $period2end = $year. "-06-30";
        $periodmonth3 = $year. "-07-01";
        $period3end = $year. "-09-30";
        $periodmonth4 = $year. "-10-01";
        $period4end = $year . "-12-31";

        $user = User::where('active', 1)->first();

        if($user == null){
            return "no one participated";
        }else if($month >= 1 && $month <= 3){
            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth1,$period1end])->get();

            if($alreadyHasWinner->isEmpty()){

                $user = User::whereBetween('created_at', [$periodmonth1,$period1end])->where('active', 1)->inRandomOrder()->first();
                if($user == null){
                    return "no user in this period";
                }else{
                    $addWinner = new Winners([
                        "user_id" => $user->id
                    ]);
                    $addWinner->save();

                    $mail = new Helpers;
                    $mail->sendMailToAdmin($adminMail, $adminTitle, $user);
                    return  $user;
                }
            }else{
                return "there already is a winner";
            }

        }else if($month >= 4 && $month <= 6){
            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth2,$period2end])->get();

            if($alreadyHasWinner->isEmpty()){

                $user = User::whereBetween('created_at', [$periodmonth2,$period2end])->where('active', 1)->inRandomOrder()->first();
                if($user == null){
                    return "no user in this period";
                }else{
                    $addWinner = new Winners([
                        "user_id" => $user->id
                    ]);
                    $addWinner->save();
                    $mail = new Helpers;
                    $mail->sendMailToAdmin($adminMail, $adminTitle, $user);
                    return  $user;
                }
            }else{
                return "there already is a winner";
            }

        }else if($month >= 7 && $month <= 9){
            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth3,$period3end])->get();

            if($alreadyHasWinner->isEmpty()){
                $user = User::whereBetween('created_at', [$periodmonth3,$period3end])->where('active', 1)->inRandomOrder()->first();
                if($user == null){
                    return "no user in this period";
                }else {
                    $addWinner = new Winners([
                        "user_id" => $user->id
                    ]);
                    $addWinner->save();
                    $mail = new Helpers;
                    $mail->sendMailToAdmin($adminMail, $adminTitle, $user);
                    return $user;
                }
            }else{
                return "there already is a winner";
            }

        }else if($month >= 10 && $month <= 12){

            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth4,$period4end])->get();

            if($alreadyHasWinner->isEmpty()){
                $user = User::whereBetween('created_at', [$periodmonth4,$period4end])->where('active', 1)->inRandomOrder()->first();
                if($user == null){
                    return "no user in this period";
                }else {
                    $addWinner = new Winners([
                        "user_id" => $user->id
                    ]);
                    $addWinner->save();
                    $mail = new Helpers;
                    $mail->sendMailToAdmin($adminMail, $adminTitle, $user);
                    /*$mail = $this->sendMailToAdmin($adminMail, $adminTitle, $user);*/
                    return $user;
                }
            }else{
                return "there already is a winner";
            }
        }else{
            return "error";
        }
    }
}
