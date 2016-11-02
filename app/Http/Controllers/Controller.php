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


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getIP(){
        $ip = "none";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip =  $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip =  $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip =  $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function checkIP(){
        $ip = $this->getIP();
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

    public function sendConfirmMail($emailTo, $first_name){

        require(base_path().'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

      //  $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.live.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'cocagiveaway@hotmail.com';                 // SMTP username
        $mail->Password = 'cocacola12';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->setFrom('cocagiveaway@hotmail.com', 'CocaCola');
        $mail->addAddress($emailTo, $first_name);     // Add a recipient
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->isHTML(false);                                  // Set email format to HTML

        $mail->Subject = 'Bedankt voor het meedoen met de CocaCola prijsvraag!';
        $mail->Body    = 'wanneer je verkozen wordt tot winnaar zul je automatisch een mail krijgen';
        $mail->AltBody = 'wanneer je verkozen wordt tot winnaar zul je automatisch een mail krijgen';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Message has been sent';
        }
    }

    public function sendMailToAdmin($emailTo, $first_name, $winner){

        require(base_path().'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

       // $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.live.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'cocagiveaway@hotmail.com';                 // SMTP username
        $mail->Password = 'cocacola12';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->setFrom('cocagiveaway@hotmail.com', 'CocaCola');
        $mail->addAddress($emailTo, $first_name);     // Add a recipient
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->isHTML(false);                                  // Set email format to HTML

        $mail->Subject = 'Wij hebben een winnaar geselecteerd voor deze periode';
        $mail->Body    = 'de winnaar is' . $winner;
        $mail->AltBody = 'de winnaar is' . $winner;

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Message has been sent';
        }
    }

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
                'image' => $request->image
            ]);

            $user->save();
            $code = $this->saveCode($request->code,$user->id);

            $mail = $this->sendConfirmMail($user->email,$user->first_name);

            return "success";
        }


        //$this->sendConfirmMail();
    }

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

        if($month >= 1 && $month <= 3){
            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth1,$period1end])->get();

            if($alreadyHasWinner->isEmpty()){
            $user = User::whereBetween('created_at', [$periodmonth1,$period1end])->inRandomOrder()->first();
            $addWinner = new Winners([
             "user_id" => $user->id
            ]);
            $addWinner->save();

                $mail = $this->sendMailToAdmin($adminMail,$adminTitle,$user);
            return  $user;
            }else{
                return "there already is a winner";
            }

        }else if($month >= 4 && $month <= 6){
            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth2,$period2end])->get();

            if($alreadyHasWinner->isEmpty()){

            $user = User::whereBetween('created_at', [$periodmonth2,$period2end])->inRandomOrder()->first();
            $addWinner = new Winners([
                "user_id" => $user->id
            ]);
            $addWinner->save();
                $mail =    $this->sendMailToAdmin($adminMail,$adminTitle,$user);
            return  $user;
            }else{
                return "there already is a winner";
            }

        }else if($month >= 7 && $month <= 9){
            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth3,$period3end])->get();

            if($alreadyHasWinner->isEmpty()){
            $user = User::whereBetween('created_at', [$periodmonth3,$period3end])->inRandomOrder()->first();
            $addWinner = new Winners([
                "user_id" => $user->id
            ]);
            $addWinner->save();
                $mail =   $this->sendMailToAdmin($adminMail,$adminTitle,$user);
            return  $user;
            }else{
                return "there already is a winner";
            }

        }else if($month >= 10 && $month <= 12){

            $alreadyHasWinner = Winners::whereBetween('created_at', [$periodmonth4,$period4end])->get();

            if($alreadyHasWinner->isEmpty()){
            $user = User::whereBetween('created_at', [$periodmonth4,$period4end])->inRandomOrder()->first();
            $addWinner = new Winners([
                "user_id" => $user->id
            ]);
            $addWinner->save();
               $mail = $this->sendMailToAdmin($adminMail,$adminTitle,$user);
                return  $user;
            }else{
                return "there already is a winner";
            }
        }else{
            return "error";
        }
    }

    public function getWinners(){

        $winners = User::has('winner')->get();

        return view('winners',['winners' => $winners]);
    }
}
