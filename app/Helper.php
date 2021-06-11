<?php

use App\Firebase;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;

function apiResponseHandler($response = [], $message = '', $status = 200)
{
    return [
        'response' => $response,
        'message' => $message,
        'status' => $status
    ];
}
function brainTreeConfig()
{
    require_once base_path() . '/vendor/braintree/braintree_php/lib/Braintree.php';       

$config = new Braintree\Configuration([
    'environment' => 'sandbox',
    'merchantId' => 'n8pd7jzw9x5syr3x',
    'publicKey' => '9ptsqjgt79cphkzc',
    'privateKey' => 'bd8344580b9e1050b785888d132cf8af'
]);
return $config;
}
function createCustomer($user)
{
    $config=brainTreeConfig();
$gateway = new Braintree\Gateway($config);       
$result = $gateway->customer()->create([
    'firstName' => $user->name,
    'email' => $user->email,
    'phone' => "+".$user->country_code.$user->phone
    
]);
return $result->customer->id;  
}
function createCustomerCard($user,$card)
{
    $config=brainTreeConfig();
$gateway = new Braintree\Gateway($config); 

$result = $gateway->customer()->update(
     $user->stripe_id,
    ['creditCard'=>[
    'number' => $card->number,
    'expirationDate' => $card->exp_month."/".$card->exp_year,
    'cvv' => $card->cvc]
   ]
    
);
if ($result->success) {

return $result->customer->creditCards[0];
}
else
{
    return  ["error"=>$result->message];
}
}
function setClientToken($aCustomerId)
{
$config=brainTreeConfig();
$gateway = new Braintree\Gateway($config);
$clientToken = $gateway->clientToken()->generate(
    ["customerId" => $aCustomerId]
);
return $clientToken;
}
function toChargeCard($id,$card_id,$amount)
{
    $config=brainTreeConfig();
$gateway = new Braintree\Gateway($config);
$updateResult = $gateway->customer()->update(
  $id,
  [
    'defaultPaymentMethodToken' => $card_id
  ]
);

$result = $gateway->transaction()->sale([
    'amount' => $amount,
    //'deviceData'=>["correlation_id"=>"26fd8775f5ef448d8772e43b08486a6e"],
    'customerId' => $id,
    'options' => [ 'submitForSettlement' => true ]
]);

if ($result->success) {
    //print_r("success!: " . $result->transaction->id);
    return $result;
} else if ($result->transaction) {
    return ["error"=>"Error processing transaction:",
    "code"=>$result->transaction->processorResponseCode,
   "message"=>$result->transaction->processorResponseText];
} else {
    
    $error=$result->errors->deepAll();
    return ["error"=>$error];
}
}
function toChargePaypal($card_id,$amount)
{
    $config=brainTreeConfig();
$gateway = new Braintree\Gateway($config);


$result = $gateway->transaction()->sale([
    'amount' => $amount,
    //'deviceData'=>["correlation_id"=>"26fd8775f5ef448d8772e43b08486a6e"],
    'paymentMethodNonce' => $card_id,
    'options' => [ 'submitForSettlement' => true ]
]);

if ($result->success) {
    //print_r("success!: " . $result->transaction->id);
    return $result;
} else if ($result->transaction) {
    return ["error"=>"Error processing transaction:",
    "code"=>$result->transaction->processorResponseCode,
   "message"=>$result->transaction->processorResponseText];
} else {
    
    $error=$result->errors->deepAll();
    return ["error"=>$error];
}
}
function refundCharge($card_id,$amount)
{
    $config=brainTreeConfig();
$gateway = new Braintree\Gateway($config);


$result = $gateway->transaction()->refund(
    ['transactionId'=>$card_id,
    'amount'=>$amount]
);

if ($result->success) {
    //print_r("success!: " . $result->transaction->id);
    return $result;
} else if ($result->transaction) {
    return ["error"=>"Error processing transaction:",
    "code"=>$result->transaction->processorResponseCode,
   "message"=>$result->transaction->processorResponseText];
} else {
    
    $error=$result->errors->deepAll();
    return ["error"=>$error];
}
}
function sendNotification()
{
    $firebase = Firebase::where('user_id', '=', $request_ride->user_id)->select('user_id', 'firebase_token', 'platform')->get();
        
        foreach ($firebase as $key => $item) {
            $notification = [
                'title' => 'Action on request ride status is '.$final_status,
                'body' => "Driver perform action on your requested ride is ".$final_status . $status == 2 ? 'Otp : '.$request_ride->sms_code : '',
                'type' => RIDE_REQUEST_ACTION,
                'data' => [
                    'type' => RIDE_REQUEST_ACTION,
                    'status' => $status,
                    'otp' => $status == 2 ? $request_ride->sms_code : ''

                ]
            ];
            sendFirebaseNotification($item->firebase_token, $notification, $item->platform);
        }

        $notify = new Notification();
        $notify->sender = Auth::user()->id;
        $notify->receiver = $request_ride->user_id;

        if ($status == 2) {
            $notify->title = "Your ride has been accepted  OTP Code ".$request_ride->booking_id;
            $message = $notify->title;
            $receiverEmail=User::find($request_ride->user_id);
            //sendMessage($number, $message);
            sendEmail($receiverEmail->email, $receiverEmail->name, $message);

        }
        if ($status == 3) {
            $notify->title = 'Your ride has been declined';
        }
        $notify->notification_message =$notify->title." ".$driver_rides->source." ".$driver_rides->destination;
        $notify->type = RIDE_REQUEST_ACTION;
        $notify->otp = $request_ride->sms_code;
        $notify->driver_ride_id = $driver_rides->user_id;

        $notify->save();


        Notification::where('type', '=', 3)
            ->where('sender', '=', Auth::user()->id)
            ->where('receiver', '=', $request_ride->user_id)
            ->delete();

        if ($status == 2) {
            //admin notification
            $admin = User::where('type', '=', 3)->select('id')->first();

            $admin_fcm = Firebase::where('user_id', '=', $admin->id)->select('firebase_token', 'platform', 'user_id')->first();
            if ($admin_fcm) {

                $notification = [
                    'title' => 'Requested Ride Accepted By Driver',
                    'body' => "User Requested Ride Accepted By Driver",
                    'type' => DRIVER_RIDE_ACCEPTED,
                    'data' => [
                        'type' => DRIVER_RIDE_ACCEPTED,
                        'message' => 'Ride Accepted By Driver',
                        'ride_id' => $driver_rides->id,
                    ]];

                sendFirebaseNotification($admin_fcm->firebase_token, $notification, $admin_fcm->platform);

                $admin_notify = new AdminNotification();
                $admin_notify->user_id = Auth::user()->id;
                $admin_notify->type = DRIVER_RIDE_ACCEPTED;
                $admin_notify->ride_id = $driver_rides->id;
                $admin_notify->ride_posted_by_driver = 1;
                $admin_notify->notification_message = 'User Requested Ride Accepted By Driver';

                $admin_notify->save();
            }
            return response()->json(apiResponseHandler(["data"=>$request_ride], 'Ride Accepted Successfully'));
        } elseif ($status == 3) {
            return response()->json(apiResponseHandler(["data"=>$request_ride], 'Ride Declined Successfully'));
        }
}
function dateBetweenFilter($allFare,$yearStartDate,$yearEndDate,$ride_date)
{
    $yearFare=array_filter($allFare,function($a)use($yearStartDate,$yearEndDate,$ride_date){
            $rideDateStart=strtotime($yearStartDate);
            $rideEndStart=strtotime($yearEndDate);
            $a=(array) $a;
           return ($a[$ride_date]>=$rideDateStart && $a[$ride_date]<=$rideEndStart);
        });
    return $yearFare;
}
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
function  dateByFilter()
{
    $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $start = new Carbon('first day of this month');
        $monthStartDate=$start->startOfMonth()->format('Y-m-d H:i');
        $end = new Carbon('last day of this month');
        $monthEndDate=$end->endOfMonth()->format('Y-m-d H:i');
        $year = date('Y');
        $yearStartDate = Carbon::create($year, 1, 1, 0, 0, 0)->format('Y-m-d H:i');
        $yearEndDate = Carbon::create($year, 12, 31, 0, 0, 0)->format('Y-m-d H:i');
        $dayStartDate=date("Y-m-d")." 00:00";
        $dayEndDate=date("Y-m-d")." 23:59";
        $dayYesterdayStart=Carbon::yesterday()->format("Y-m-d H:i");
        $dayYesterdayEnd=substr($dayYesterdayStart,0,10)." 23:59";
        $lastYear=$year-1;
        $lastyearStartDate = Carbon::create($lastYear, 1, 1, 0, 0, 0)->format('Y-m-d H:i');
        $lastyearEndDate = Carbon::create($lastYear, 12, 31, 0, 0, 0)->format('Y-m-d H:i');
        $start = new Carbon('first day of last month');
        $lastmonthStartDate=$start->startOfMonth()->format('Y-m-d H:i');
        $end = new Carbon('last day of last month');
        $lastmonthEndDate=$end->endOfMonth()->format('Y-m-d H:i');
        $start = new Carbon('last sunday');
        $lastweekEndDate=$start->format('Y-m-d H:i');
        $end = $start->subDays("6");
        $lastweekStartDate=$end->format('Y-m-d H:i');
       return [
        "dayStartDate"=>$dayStartDate,
        "dayEndDate"=>$dayEndDate,
        "yearStartDate"=>$yearStartDate,
        "yearEndDate"=>$yearEndDate,
        "weekStartDate"=>$weekStartDate,
        "weekEndDate"=>$weekEndDate,
        "monthStartDate"=>$monthStartDate,
        "monthEndDate"=>$monthEndDate,
        "yesterdayStartDate"=>$dayYesterdayStart,
        "yesterdayEndDate"=>$dayYesterdayEnd,
        'lastmonthStartDate'=>$lastmonthStartDate,
        'lastmonthEndDate'=>$lastmonthEndDate,
        'lastweekStartDate'=>$lastweekStartDate,
        'lastweekEndDate'=>$lastweekEndDate,
        'lastyearStartDate'=>$lastyearStartDate,
        'lastyearEndDate'=>$lastyearEndDate
       ];
}
function sendFirebaseNotification($token, $notification, $platform)
{
    $url = 'https://fcm.googleapis.com/fcm/send';

    $headers = array(
        'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
        'Content-Type: application/json'
    );

    $body = [];

    if ($platform == 1) {
        $inputData = $notification['data'];
        $inputData['title'] = $notification['title'];
        $inputData['body'] = $notification['body'];
        $body = [
            'to' => $token,
            'data' => $inputData,
        ];
    } else if ($platform == 2) {
        $iosData = $notification;

        $iosData['data'] = $notification;

        $body = [
            'to' => $token,
            "content_available" => true,
            "mutable_content" => true,
            'notification' => $iosData

        ];
    } else if ($platform == 3) {

        $body = [
            "to" => $token,
            "notification" => $notification
        ];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    $result = curl_exec($ch);

    Log::info($result);
    Log::info($token);

    $status = json_decode($result, true);

    if ($status['failure'] == 0) {
        curl_close($ch);
        return false;
    } else {
        Log::info('Error Log');
        if ($token) {
            Firebase::where('firebase_token', '=', $token)->delete();
        }
        curl_close($ch);
        return true;
    }
}

function sendEmail($to, $name, $html)
{
    $mail = new PHPMailer(true);

    // $mail->SMTPDebug = false;                      // Enable verbose debug output
    // $mail->isSMTP();                                            // Send using SMTP
    // $mail->Host = env('MAIL_HOST');                    // Set the SMTP server to send through
    // $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    // $mail->Username = env('MAIL_USERNAME');// SMTP username
    // $mail->Password = env('MAIL_PASSWORD');                               // SMTP password
    // $mail->SMTPSecure = env('MAIL_ENCRYPTION');         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    // $mail->Port = env('MAIL_PORT');
    // $mail->setFrom(env('MAIL_FROM'), 'noreply'); //
    // $mail->addAddress($to, $name);
    // $mail->isHTML(true);
    // $mail->Subject = 'Hitch SMS';
    // $mail->Body = $html;
    // $mail->send();

$subject = 'Hitch Notification';
$message = $html;
$headers = "From:Hitch" . env('MAIL_FROM');
if(mail($to,$subject,$message, $headers)) {
return true;
} else {
return false;
}

// $mail->isSMTP();
// $mail->SMTPDebug = 2;
// $mail->Host = env('MAIL_HOST');
// $mail->Port = 587;
// $mail->SMTPAuth = true;
// $mail->Username = env('MAIL_USERNAME');// SMTP username
// $mail->Password = env('MAIL_PASSWORD');  
// $mail->setFrom(env('MAIL_FROM'), 'noreply'); //
// $mail->addAddress($to, $name);
// $mail->Subject = 'Testing PHPMailer';
// $mail->msgHTML($html);
// $mail->Body = 'This is a plain text message body';
// //$mail->addAttachment('test.txt');
// if (!$mail->send()) {
// echo 'Mailer Error: ' . $mail->ErrorInfo;
// } else {
// echo 'The email message was sent.';
// }
//     return true;
}

function sendMessage($number, $message)
{
    
   /* $sid = 'AC72ce45bbc4633bd84056d6a48d1473d3';
    $token = '928440183c01ecbe23774c738f3470fe';
    $client = new Client($sid, $token);

// Use the client to do fun stuff like send text messages!
$response=$client->messages->create(
    // the number you'd like to send the message to
    $number,
    [
        // A Twilio phone number you purchased at twilio.com/console
        'from' => '+1509851-8548',
        // the body of the text message you'd like to send
        'body' => $message
    ]
);

    /*if ($err) {
        return response()->json(apiResponseHandler([], "cURL Error #:" . $err, 400), 400);
    } else {
        return response()->json(apiResponseHandler($response, 'success'));
    }*/
    return response()->json(apiResponseHandler([], 'success'));

}
function randomFunctionNumber($n)
{
    $characters = '0123456789';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}
function randomFunction($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function getRideDistance($lat1, $lat2, $lat3, $lat4)
{

    $key = 'AIzaSyBHI90JunDZppnII2PaVRZaR61CkSiuK5w';
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $lat1 . "," . $lat2 . "&destinations=" . $lat3 . "," . $lat4 . "&mode=driving&language=en&key=" . $key;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    Log::info($result);
    curl_close($ch);

    $response = json_decode($result, true);

    $distance = $response['rows'][0]['elements'][0]['distance']['text'];
    $time = $response['rows'][0]['elements'][0]['duration']['text'];

    return array('distance' => $distance, 'time' => $time);
}


function sendMessageNotification($data, $chat_id)
{
    $url = 'https://drupp-app.firebaseio.com/admin_chat/' . $chat_id . ".json";
    $key = 'AIzaSyDOcDbk1PO_qgVJdLDkmNJBF6LAnugEwBM';

    $headers = array(
        'Authorization: key = ' . $key,
        'Content - Type: application / json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);

    if ($result === FALSE) {
        curl_close($ch);
        return FALSE;
    } else {
        curl_close($ch);
        return TRUE;
    }
}
function getPercentage($amount,$per)
{
    $amount=str_replace(",","",$amount);
    $amount=round($amount);
    $v=$amount*$per;
    $out=$v/100;
    return number_format($out,2);
    
}
