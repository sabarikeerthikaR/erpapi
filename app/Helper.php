<?php
use App\Models\notification;
use App\Models\activities;
use App\Models\users;
use App\notifications\notifications;
/**
 * All The Common Helper Function Should be Defined
 * Please make sure to check if function exists or not
 * Please add Proper Comments and Info text For All Function
 * Keep the function definition simple and standard
 * Please Keep the Function name standard and follow <i>camelCase</I>
 * Please try to define the parameter type if possible
 * All The Database Queries should be defined in try,catch block and handle the error proper
 */
function getDateFormat()
    {
      return 'Y-m-d H:i:s.u';
    }
function activities($action_performer,$action_to,$action_title,$description,$read_status)
{
       $activities=activities::create([

        'action_performer'  =>$action_performer,
        'action_to'  =>$action_to,
        'action_title'    =>$action_title,
         'description'  =>$description,
        'read_status'  =>$read_status,
         ]);
       notification($action_performer,$action_to,$action_title,$description,$read_status);
}

function notification($action_performer,$action_to,$action_title,$description,$read_status)
{
    $notification=notification::create([

        'action_performer'  =>$action_performer,
        'action_to'  =>$action_to,
        'action_title'    =>$action_title,
         'description'  =>$description,
        'read_status'  =>$read_status,
         ]);
         email($notification);
}
function email($request)
{
$user = User::where('email', $request->email)->first();
 $user->notify(
                new Notifications('notification')
            );
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

function apiResponseHandler($response = [], $message = '', $status = 200)
{
    return [
        'response' => $response,
        'message' => $message,
        'status' => $status
    ];
}
if(!function_exists('testFunction')){
    /**
     *   This is Test function Just to Show you how functions should be defined
     *
     * @param string $name <p><i>Name</i> Should be string .</p>
     * @param int $age     <p><i>Age</i> Should be Integer .</p>
     * @param mixed $gender      <p><i>Gender</i> Can be mixed .</p>
     * @return array       <p>['name','age','gender','user']</p>
     */
    function testFunction(string $name, int $age, $gender){
        try{
            /* Write Code For Operation */
            $user  = \App\Models\User::where('name',$name)->get();
        }
        catch(\Exception $e){
            /* Handel The Exception */
        }
        return ["name"=>$name,"age"=>$age,"gender"=>$gender,'user'=>$user];
    }
}

if(!function_exists('getSunBurstData')){
    /**
     * <p><i>getSunBurstData</i></p> The Required Dummy Data
     * @param string $dataName  <p><i>Name</i> Directory/Folder Path .</p>
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    function getSunBurstData(string $dataName)
    {
        return config("custom.sunburst.{$dataName}");
    }
}


if(!function_exists('getFileNames')){
    /**
     * <p><i>getFileNames</i></p> Function That Provides the file names in a give directory
     * @param string $path  <p><i>Path</i> Should be string .</p>
     * @return array
     */
    function getFileNames(string $path): array
    {
        $fileNames = [];
        $files = \File::allFiles($path);

        foreach($files as $file) {
            array_push($fileNames, pathinfo($file)['filename']);
        }
        return $fileNames;
    }
}

if(!function_exists('getInstituteTypeId')){
    /**
     * <p><i>getInstituteTypeId</i></p> function will provide institute type id.
     * @param string $type  <p><i>Path</i> Type Name i.e university .</p>
     * @return string
     */
    function getInstituteTypeId(string $type): string
    {
        $typeId = \App\Models\InstitutesTypes::where('type',strtolower($type))->first()->id;
        return $typeId;
    }
}
?>
