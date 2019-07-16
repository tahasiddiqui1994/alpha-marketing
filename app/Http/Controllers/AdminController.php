<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use App\Admin;
use App\Role;
use App\Team;
use App\Merchant;
use App\RoughCallBack;
use App\Mail\MyMail;
use Mail;
use DB;
use Auth;
use Pusher\Pusher ;
use Carbon\Carbon ;
use App\sessionEye ;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function index() {

        $allUser =  User::all();
        $userCount = $allUser->count();
        $userCurrent = User::where('del','0')->count();
        $userActive = User::where('disabled','0')->where('del','0')->count();
        $recentUser =  User::orderBy('created_at','desc')->take(10)->get();
        $choice = ['DMP','Submit'];
        $recentMessage = Message::whereIn('prevStatus',$choice)->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->get();
        $recentMessageStats = Message::whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->get();
        $recentMessageCount = $recentMessage->count();
        $messageCount = Message::count();
         $agents_count = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
                $q->where('del','!=','1');
                $q->where('disabled','!=','1');
            }
        )->count();

        $closers_count = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
                $q->where('del','!=','1');
                $q->where('disabled','!=','1');
            }
        )->count();

        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        foreach($recentMessage as $message) {
            $message->userName = $allUser->find($message->userID)->name;
        }
        
        $StatusChoice = ['Approved','CR Approved','DMP Approved'];

        $noOfTransfer = $recentMessageStats->where('closer','!=','None')->count();
        $noOfVerfied = $recentMessageStats->where('prevStatus','=','Submit')->count();
        $noOfDMP = $recentMessageStats->where('prevStatus','=','DMP')->count();
        $noOfCRAgent = $recentMessageStats->where('closer','=','None')->where('prevStatus','=','CRB')->count();
        $noOfCRCloser = $recentMessageStats->where('closer','!=','None')->where('prevStatus','=','CRB')->count();
        $noOfApproved = $recentMessageStats->whereIn('status',$StatusChoice)->count();
       
        // echo $noOfTransfer.'<br>';
        // echo $noOfVerfied.'<br>';
        // echo $noOfApproved.'<br>';
        // echo $noOfCRAgent.'<br>';
        // echo $noOfCRCloser.'<br>';
        
        $notifications = AdminController::getNotificationAdmin() ;

        return view('admin',compact('messageCount','userCount','recentMessage','allUser','recentUser','recentMessageCount','allMerchants','userCurrent','userActive','agents_count',
        'closers_count','noOfTransfer','noOfVerfied','noOfDMP','noOfCRAgent','noOfCRCloser','noOfApproved', 'notifications'));
    }

    public function getNotificationAdmin(){

        $notifications = DB::table('messages')
        ->join('roughcallbacks', 'roughcallbacks.message_id', '=', 'messages.id')
        ->select('*')
        ->where('roughcallbacks.type','=', 'Admin')->where('seen', '=', '0')->where('messages.created_at','>=', Carbon::today()->subDays(2)->addHours(13))
        ->groupBy('messages.id')
        ->get()
        ->toArray() ;

        return ($notifications) ;
    }

    public function getNotification($userType,$userIdentity){
		//echo $userType . $userIdentity;
		If($userType == '1'){
			$query = "select max(r.id) as 'main_id',r.date_time,r.type,r.seen, m.* from messages as m join roughcallbacks as r on m.id = r.message_id where r.date_time > DATE_SUB(curdate(), INTERVAL 1 DAY) and m.userID = '$userIdentity' and r.type like '%Agent%' and r.seen = 0 and (m.status = 'RoughCall' or m.status = 'CallBack' or m.status = 'RNA') GROUP by id ORDER by date_time DESC";
		}else if($userType == '2'){
			$query = "select max(r.id) as 'main_id',r.date_time,r.type,r.seen, m.* from messages as m join roughcallbacks as r on m.id = r.message_id where r.date_time > DATE_SUB(curdate(), INTERVAL 1 DAY) and m.closer = '$userIdentity' and r.type like '%Closer%' and r.seen = 0 and (m.status = 'CallBack' or m.status = 'RNA') GROUP by id ORDER by date_time DESC";
		}else{
		    $query = "select max(r.id) as 'main_id',r.date_time,r.type,r.seen, m.* from messages as m join roughcallbacks as r on m.id = r.message_id where r.date_time > DATE_SUB(curdate(), INTERVAL 1 DAY) and r.type like '%Admin%' and r.seen = 0 GROUP by m.id ORDER by date_time DESC";
		}
       // $notifications = DB::table('messages as m')->join('roughcallbacks as r', 'm.id', '=', 'r.message_id')->select(DB::raw("max(r.id) as 'main_id', m.*,r.date_time, r.type"))->where('m.userID', '=', $userIdentity)->where('r.type','like','\'%Agent%\'')->groupBy('m.id')->get();
		//echo $query; die;
 		//$notifications = collect(DB::select(DB::raw("select select max(r.id) as 'main_id',r.date_time,r.type,r.seen, m.* from messages as m join roughcallbacks as r on m.id = r.message_id where m.closer = 'closer' and r.type like '%Closer%' and r.seen = 0 and (m.status = 'CallBack' or m.status = 'RNA')")))->groupBy('id');
        //$notifications->toArray();
		$notifications = DB::select(DB::raw($query));
// 		echo '<pre>';
// 		print_r($notifications); die;
// 		echo '</pre>';
		return response()->json($notifications);
	}

    public function AllUser() {
        $messageCount = Message::count();
        $agents = null;
        $closers = null;
        $agents = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
                $q->where('del','!=','1');
            }
        )->get();

        $closers = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
                $q->where('del','!=','1');
            }
        )->get();
        
        $status = -1;
        $disabledAgents = null;
        $disabledClosers = null;
        $allMerchants = null;
        
        if(User::count() > 0){
            $status = User::orderBy('id', 'desc')->first()->active;
        }
        
        if($agents->count()>0){
            $disabledAgents = $agents->where('disabled','=',1);
            $agents = $agents->where('disabled','=',0);
        }
        
        if($closers->count()>0){
            $disabledClosers = $closers->where('disabled','=',1);
            $closers = $closers->where('disabled','=',0);
        }
        
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('allusers',compact('agents','closers','disabledAgents','disabledClosers','messageCount','status','allMerchants', 'notifications'));
    }
    
     public function AllAdmin() {
        if(Auth::guard('admin')->user()->job_title == null){ 
            return redirect('/admin')->with('message','Unauthorized Person');
        }
            
            $messageCount = Message::count();
           
            $status =0;
            $disabledAdmins = null;
            $admins=null;    
            
           if(Admin::count()>1){
            $status = Admin::orderBy('id', 'desc')->first()->active;

            $disabledAdmins = Admin::where('job_title','=',null)->where('disabled',1)->get();

            $admins = Admin::where('job_title','=',null)->where('disabled','=',0)->where('del','=',0)->get();
           }

            $allMerchants =  Merchant::OrderBy('id','desc')->get();

            $notifications = AdminController::getNotificationAdmin() ;
            
            return view('alladmins',compact('admins','disabledAdmins','messageCount','status','allMerchants', 'notifications'));
        
    }


    public function AllTeam(){

        $messageCount = Message::count();
        $teams = Team::all();
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        return view('allteams',compact('teams','messageCount','allMerchants'));
    }

    public function AllMessages() {

      $allMessages =  Message::OrderBy('id','desc')->get();
      $messageCount = Message::count();
      $allMerchants =  Merchant::OrderBy('id','desc')->get();

      $from = null;
      $to = null;

      foreach ($allMessages as $message) {
        $message->userName = User::find($message->userID)->name;
    
        foreach ($allMerchants as $merchant) {
          if(!is_null($message->merchantID)){
          $message->merchantName = Merchant::find($message->merchantID)->name;
          }
        }
       
        if($message->updatedBy == '0'){
            $message->updatedBy = 'none';
        }
        elseif($message->updatedBy[0] == 'A'){
             $updatedBy =  substr($message->updatedBy, 1);
             $upDatedBy = Admin::where('id',$updatedBy)->first()->name;
             $message->updatedBy = $upDatedBy;
        }
        else{
             $upDatedBy = User::where('id',$message->updatedBy)->first()->name;
             
             if(!is_null($upDatedBy)){
                  $message->updatedBy = $upDatedBy;
                 
             }
             else{
                 $message->updatedBy  = 'none';
                 
             }
        }
        
      }
      
      $notifications = AdminController::getNotificationAdmin() ;

      return view('allMessages',compact('allMessages','messageCount','allMerchants','from','to', 'notifications'));
    }

    public function ActiveToggle(Request $input){

      $admin = Admin::where('email',$input->email)->first();

      if (password_verify($input->password, $admin->password)) {
        $allUser = User::all();
        foreach ($allUser as $user) {
          if($input->status == 0)
          $user->active = 1;
          else{
            $user->active = 0;
          }
          $user->save();
        }
          return redirect()->back()->with('message', 'All user status successfully updated');

      } else {
          return redirect()->back()->with('message', 'Invalid Password');
      }


    }
    
     public function ActiveToggleAdmin(Request $input){
       
      if(Auth::guard('admin')->user()->job_title == null){ 
            return redirect('/admin')->with('message','Unauthorized Person');
      }
      
      $admin = Admin::where('email',$input->email)->first();

      if (password_verify($input->password, $admin->password) && $admin->job_title == 'super') {
        $allAdmin = Admin::all();
        foreach ($allAdmin as $admin) {
          if($input->status == 0 && $admin->job_title != 'super')
            $admin->active = 1;
          else{
            $admin->active = 0;
          }
          $admin->save();
        }
          return redirect()->back()->with('message', 'All admin status successfully updated');

      } else {
          return redirect()->back()->with('message', 'Invalid Password');
      }


    }
    
    public function attendance() {
        $attendance = sessionEye::get()->groupBy('userID')->toArray() ;

        foreach ($attendance as $user) {
            foreach ($user as $session) {
                
            }
        }
        
        $notifications = AdminController::getNotificationAdmin() ;

        return view('auth.attendance',compact('notifications'))->with('attendance', $attendance) ;
    }

    public function messageMonthly(Request $input){
      $messageCount = Message::count();

      $from = date('Y-m-d', strtotime($input->startDate))." 00:00:00";
      $to = date('Y-m-d', strtotime($input->endDate))." 23:59:59";
      $allMessages = null;
    
      
     
      $allMessages = Message::whereBetween('updated_at',[$from, $to])->get();

      $allMerchants =  Merchant::OrderBy('id','desc')->get();

      foreach ($allMessages as $message) {
        $message->userName = User::find($message->userID)->name;
        foreach ($allMerchants as $merchant) {
          if(!is_null($message->merchantID)){
          $message->merchantName = Merchant::find($message->merchantID)->name;
          }
        }
      }

      $notifications = AdminController::getNotificationAdmin() ;
      return view('allMessages',compact('allMessages','messageCount','allMerchants','from','to', 'notifications'));
      //return response()->json($Messages);

    }

    public function showRegistrationForm() {
        $messageCount = Message::count();
        $roles = Role::get();
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view ('auth.register', compact('roles','messageCount','allMerchants', 'notifications'));
    }
    
    public function showRegistrationFormAdmin() {
        $messageCount = Message::count();
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view ('auth.registerAdmin', compact('messageCount','allMerchants', 'notifications'));
    }

    public function addUser() {
        $messageCount = Message::count();
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('adduser', compact('roles','messageCount','allMerchants', 'notifications'));
    }
    
    

    public function viewUser($id) {
        $messages = Message::all();
        $messageCount = $messages->count();

        $allMerchants =  Merchant::OrderBy('id','desc')->get();

        $currentMonth = date('m');
        $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();

        $user = User::select('id','name','showpass')->find($id);

        $id = $user->id;
        $name = $user->name;
        $showpass = $user->showpass;

        $role = $user->roles->first()->name;
        if($role == 'agent') {
            $messages = $messages->where('userID','=',$user->id);
        } else {
            $messages = $messages->where('closer','=',$user->name);
        }

        $user = $user->getStats($messages);
        $notifications = AdminController::getNotificationAdmin() ;

        return view('auth.viewuser', compact('user','messages','messageCount', 'allMerchants', 'notifications'));
    }
    
    

    public function insertUser(Request $input) {
            $role = Role::where('id', $input->role)->first();

            $user = User::create([
                'name' => $input->name,
                'password' => 0,
                'showpass' => $input->password,
                'disabled' => 0,

            ]);

            $user->basicSalary = $input->basicSalary;

            //bind role to user
            $user->assignRole($role);
            $user->setPasswordAttribute($input->password);
            $user->showpass = $input->password;
            $user->update();

         return redirect('/allUsers')->with('message', 'User has been successfully created');
    }
    
    public function insertAdmin(Request $input) {
        
        $admin = Auth::guard('admin')->user();
            
        
        if (password_verify($input->adminPassword, $admin->password) && $admin->job_title='super') {
            $admin = Admin::create([
                'name' => 'admin',
                'email' => $input->email,
                'password' => Hash::make($input->password),
                'job_title' => null,

            ]);

            return redirect('/allAdmins')->with('message', 'Admin has been successfully created');
        }
        else{
            return redirect('/allAdmins')->with('message', 'Unauthorized Person');
        }
    }


    public function editUser($id) {
        $messageCount = Message::count();
        $user = User::where('id','=',$id)->first();
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('edituser',compact('user','messageCount','allMerchants', 'notifications'));
    }
    
     public function editAdmin($id) {
        $messageCount = Message::count();
        $admin = Admin::where('id','=',$id)->first();
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('editadmin',compact('admin','messageCount','allMerchants', 'notifications'));
    }

    public function updateUser(Request $input) {
        $user = User::where('id','=',$input->id)->first();
        $user->name = $input->username;
        $user->setPasswordAttribute($input->password);
        $user->showpass = $input->password;
        $user->basicSalary = $input->basicSalary;

        try {
            $user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/allUsers')->with('warning', 'please recheck all values');
        }

        return redirect('/allUsers')->with('message', 'User has been successfully updated');
    }
    
    public function updateAdmin(Request $input) {
        
        if(Auth::guard('admin')->user()->job_title == null){ 
            return redirect('/admin')->with('message','Unauthorized Person');
        }
        
        $superadmin = Auth::guard('admin')->user();
         
            if(password_verify($input->superadminpassword, $superadmin->password) && $superadmin->job_title == 'super') {
                
                $admin = Admin::where('id','=',$input->adminID)->first();
                $admin->email = $input->email;
                if($input->password != ''){
                     $admin->password = Hash::make($input->password);
                }
                
                
                $admin->save();
                
                return redirect('/allAdmins')->with('message', 'Admin has been deleted successfully');
            } 
          
            else {
              return redirect('/allAdmins')->with('message', 'Invalid Password');
            }
        
       
    }

    public function disableUser($id) {
        $user = User::find($id);
        $user->disabled = 1;
        $user->update();

        return redirect('/allUsers')->with('message', 'User has been disabled successfully');
    }
    
     public function disableAdmin($id) {
       
       if(Auth::guard('admin')->user()->job_title == null){ 
            return redirect('/admin')->with('message','Unauthorized Person');
        }     
      
        $admin = Admin::find($id);
        $admin->disabled = 1;
        $admin->update();
        return redirect('/allAdmins')->with('message', 'Admin has been disabled successfully');

    }

    public function deleteUser($id) {
        $user = User::find($id);
        $user->del = 1;
        $user->update();

        return redirect('/allUsers')->with('message', 'User has been deleted successfully');
    }
    
     public function deleteAdmin(Request $input) {
        
        if(Auth::guard('admin')->user()->job_title == null){ 
            return redirect('/admin')->with('message','Unauthorized Person');
        }          
          $superadmin = Auth::guard('admin')->user();
         
          if (password_verify($input->password, $superadmin->password) && $superadmin->job_title == 'super') {
            $admin = Admin::find($input->adminID);
            $admin->del = 1;
            $admin->update();
    
            return redirect('/allAdmins')->with('message', 'Admin has been deleted successfully');
          } 
          
          else {
              return redirect()->back()->with('message', 'Invalid Password');
          }
       
    }

    public function enableUser($id) {
        $user = User::find($id);
        $user->disabled = 0;
        $user->update();

        return redirect('/allUsers')->with('message', 'User has been Enabled successfully');
    }
    
     public function enableAdmin($id) {
        
        if(Auth::guard('admin')->user()->job_title == null){ 
            return redirect('/admin')->with('message','Unauthorized Person');
        }   

      
        $admin = Admin::find($id);
        $admin->disabled = 0;
        $admin->update();

        return redirect('/allAdmins')->with('message', 'Admin has been Enabled successfully');
     
    }

    public function getRecentMessages() {
        $choice = ['DMP','Submit'];
        $recentMessages = Message::select('id','closer','customername','fees','status','prevStatus','userID','text','contactNo')->whereIn('prevStatus',$choice)->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->get();
         
        foreach($recentMessages as $message) {
            $username = User::find($message->userID)->name;
            $message->username = User::find($message->userID)->name;
        }

        return response()->json($recentMessages);
    }

    public function showMessage($id) {
        $messageCount = Message::count();

        $message = Message::find($id);
        $message->username = User::find($message->userID)->name;
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        
        $notifications = AdminController::getNotificationAdmin() ;
       
        return view('showmessage',compact('message','messageCount','allMerchants', 'notifications'));
    }

    public function editMessage($id) {
        $messageCount = Message::count();
        $message = Message::find($id);
        
        $message->callBackType = 'none';
        if(!is_null(RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->first())){
             $PreCallBack = RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->first();
             $message->callBackType = $PreCallBack->type;    
        }
       
        $message->userName = User::find($message->userID)->name;
        
        $agent =  User::find($message->userID);
        $allmessages = Message::where('userID',$message->userID)->get();
        $agentStats = $agent->getStats($allmessages);
        
        $closer =  User::where('name',$message->closer)->first();
        // echo $message->closer;
        // die;
        $closerStats =null;
        if(!is_null($closer)){
           $allmessages = Message::where('closer',$message->closer)->get();
           $closerStats = $closer->getStats($allmessages);
        }
        $closers = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
            }
        )->where('disabled','=',0)->get();

        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('editmessage', compact('message','messageCount','closers','allMerchants','agentStats','closerStats', 'notifications'));
    }

    public function updateMessage(Request $input) {

        $Object = new \stdClass() ;
        $newMessage = Message::find($input->id);
       
        if( ($newMessage->status == 'CallBack') && ($newMessage->status == $input->status) ){
            $PreCallBack = RoughCallBack::where('message_id',$newMessage->id)->OrderBy('updated_at','DESC')->first();    
            // RoughCallBack::destroy($PreCallBack->id);
            $newMessage->allowcallback = $input->allowcallback;
            $PreCallBack->message_id = $newMessage->id;

            $dateTime = date_create($input->Date);
            $dateTime = date_format($dateTime,"Y/m/d H:i:s");

            $PreCallBack->date_time = $dateTime;
            $PreCallBack->type= $input->callbackType;
            $PreCallBack->save();
        }
        
        if($input->status == 'CallBack') {
            
            $newMessage->allowcallback = $input->allowcallback;
            
            $callBack = new RoughCallBack();
            $callBack->message_id = $newMessage->id;

            $dateTime = date_create($input->Date);
            $dateTime = date_format($dateTime,"Y/m/d H:i:s");

            $callBack->date_time = $dateTime;
            $callBack->type= $input->callbackType;
            $callBack->save();
         
        }
         if($input->status == "RNA" || $input->status == "BankAuth" ||  $input->status == "Decline" || $input->status == "Approved" ||  $input->status == "CR Approved" ||  $input->status == "DMP Approved" || $input->status == "Submit"){
                 
                    
            $callBack = new RoughCallBack();
            $callBack->message_id = $newMessage->id;
            $callBack->type = $input->callbackType;
            $callBack->date_time = date('Y-m-d h:i:s');
            
            // $callBack->type= "Agent/Closer";
            
            $callBack->save();

            $Object->type = $callBack->type ;
            $Object->date_time = $callBack->date_time ;
        
        }
        $newMessage->text = $input->message;
        $newMessage->closer = $input->closer;
        $newMessage->customername = $input->customername;
        $newMessage->status = $input->status;
        $newMessage->fees = $input->fee;
        $newMessage->finalFee = $input->finalFee;
        $newMessage->allowcallback = $input->allowcallback;
        $newMessage->note = $input->note;
        $newMessage->contactNo = $input->contactNo;
        
        if($input->status == 'RNA'){
            $newMessage->allowcallback = $input->allowcallback;    
        }

        if($input->status == 'Approved'){
            $newMessage->returnType = $input->returnType;
            $newMessage->returnAmount = $input->returnAmount;    
        }

        $newMessage->updatedBy = "A".Auth::user()->id;
        $newMessage->update();

        $pusher = AdminController::getPusher() ;

        $Object->customername = $newMessage->customername ;
        $Object->prevStatus = $newMessage->prevStatus ;

        $pusher->trigger('subscribed', 'theEvent', $Object) ;


        return redirect('/allMessages')->with('message', 'Your message has been successfully UPDATED!');
        // return redirect('admin')->with('message', 'Your message has been successfully UPDATED!');
    }

    public function getPusher() {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
            'debug' => true,
          );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        return $pusher ;
    }
    public function deleteMessage(Request $request) {
        $message =Message::destroy($request->messageId);
        return '{"status":"1"}';
    }

    public function updatePass(Request $request) {

        $oldpass = $request->oldpass;
        $newpass = $request->newpass;
        $confirmpass = $request->confirmpass;

        if($newpass != $confirmpass) {
             return redirect()->back()->with('warning', 'New Password and Confirm Password don\'t match.');
        } else {

            if( Hash::check($oldpass,Auth::user()->password) ) {
                $admin = Admin::find(Auth::user()->id);
                $admin->password = bcrypt($newpass);
                $admin->update();

                return redirect()->back()->with('message', 'Password update successful.');
            } else {
                return redirect()->back()->with('warning', 'Incorrect Old Password.');
            }
        }
    }

    public function AmountStats() {
        $messages = Message::all();
        $messageCount = $messages->count();

        $currentMonth = date('m');
        $from = null;
        $to = null;
        $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();

        $Agents = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
            }
        )->get();

        $agents[] = new User();
        $i = 0;
        foreach($Agents as $Agent) {
            $Messages = $messages->where('userID','=',$Agent->id);
            $agents[$i] = $Agent->getStats($Messages);
            $i++;
        }

        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('auth.amountstats', compact('agents', 'messageCount','allMerchants','from','to', 'notifications'));
    }

    public function AmountStatsMonthly(Request $input) {
         
        $messages = Message::all() ;
         $messageCount = $messages->count();
         $messages = null;
         $currentDate = explode('/', $input->inputDate);

    
        $from = date('Y-m-d', strtotime($input->startDate))." 00:00:00";
        $to = date('Y-m-d', strtotime($input->endDate))." 23:59:59";

        $messages =  Message::whereBetween('updated_at',[$from, $to])->get();
       
        $Agents = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
            }
        )->get();

        $agents[] = new User();
        $i = 0;
        foreach($Agents as $Agent) {
           
            $Messages = $messages->where('userID','=',$Agent->id);
            if(!is_null($Messages)){
                $agents[$i] = $Agent->getStats($Messages);
                $i++;
            }
        }

        $Closers = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
            }
        )->get();

        $closers[] = new User();
        $i = 0;
        foreach($Closers as $Closer) {
            $Messages = $messages->where('closer','=',$Closer->name);
            if(!is_null($Messages)){
                $closers[$i] = $Closer->getStats($Messages);
                $i++;
            }
        }
       
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('auth.amountstats', compact('agents', 'closers', 'messageCount','allMerchants','from','to', 'notifications'));
        //return response()->json([$agents,$closers]);
       
    }

    public function DMPStats() {
        $messages = Message::all();
        $messageCount = $messages->count();

        $currentMonth = date('m');
        $from = null;
        $to = null;
        $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();

        $Agents = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
            }
        )->get();

        $agents[] = new User();
        $i = 0;
        foreach($Agents as $Agent) {
            $Messages = $messages->where('userID','=',$Agent->id);
            $agents[$i] = $Agent->getStats($Messages);
            $i++;
        }

        $allMerchants =  Merchant::OrderBy('id','desc')->get() ;
        $notifications = AdminController::getNotificationAdmin() ;
        return view('auth.dmpstats', compact('agents', 'messageCount','allMerchants','from','to', 'notifications')) ;
    }
    
    public function DMPStatsMonthly(Request $input) {
        $messages = Message::all() ;
        $messageCount = $messages->count();
        $messages = null;
        $currentDate = explode('/', $input->inputDate);

   
       $from = date('Y-m-d', strtotime($input->startDate))." 00:00:00";
       $to = date('Y-m-d', strtotime($input->endDate))." 23:59:59";

       $messages =  Message::whereBetween('updated_at',[$from, $to])->get();
      
       $Agents = User::whereHas(
           'roles', function($q) {
               $q->where('name', 'agent');
           }
       )->get();

       $agents[] = new User();
       $i = 0;
       foreach($Agents as $Agent) {
          
           $Messages = $messages->where('userID','=',$Agent->id);
           if(!is_null($Messages)){
               $agents[$i] = $Agent->getStats($Messages);
               $i++;
           }
       }

       $Closers = User::whereHas(
           'roles', function($q) {
               $q->where('name', 'closer');
           }
       )->get();

       $closers[] = new User();
       $i = 0;
       foreach($Closers as $Closer) {
           $Messages = $messages->where('closer','=',$Closer->name);
           if(!is_null($Messages)){
               $closers[$i] = $Closer->getStats($Messages);
               $i++;
           }
       }
      
       $allMerchants =  Merchant::OrderBy('id','desc')->get();
       $notifications = AdminController::getNotificationAdmin() ;
       return view('auth.dmpstats', compact('agents', 'messageCount','allMerchants','from','to', 'notifications'));
       
      
   }

    public function CRBStats() {
        $messages = Message::all();
        $messageCount = $messages->count();

        $currentMonth = date('m');
        $from = null;
        $to = null;
        $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();

        $Agents = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
            }
        )->get();
        
         $Closers = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
                $q->where('del','!=','1');
                $q->where('disabled','!=','1');
            }
        )->get();

        $agents[] = new User();
        $i = 0;
        foreach($Agents as $Agent) {
            $Messages = $messages->where('userID','=',$Agent->id);
            $agents[$i] = $Agent->getStats($Messages);
            //print_r($agents[$i]) ;
            
            $i++;
        }

        $Closers = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
            }
        )->get();

        $closers[] = new User();
        $i = 0;
        foreach($Closers as $Closer) {
            $Messages = $messages->where('closer','=',$Closer->name);
            $closers[$i] = $Closer->getStats($Messages);
            $i++;
        }
        
       

        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('auth.crbstats', compact('agents', 'closers','messageCount','allMerchants','from','to', 'notifications'));
    }

    public function CRBStatsMonthly(Request $input) {
        $messages = Message::all() ;
        $messageCount = $messages->count();
        $messages = null;
        $currentDate = explode('/', $input->inputDate);

   
       $from = date('Y-m-d', strtotime($input->startDate))." 00:00:00";
       $to = date('Y-m-d', strtotime($input->endDate))." 23:59:59";

       $messages =  Message::whereBetween('updated_at',[$from, $to])->get();
      
       $Agents = User::whereHas(
           'roles', function($q) {
               $q->where('name', 'agent');
           }
       )->get();

       $agents[] = new User();
       $i = 0;
       foreach($Agents as $Agent) {
          
           $Messages = $messages->where('userID','=',$Agent->id);
           if(!is_null($Messages)){
               $agents[$i] = $Agent->getStats($Messages);
               $i++;
           }
       }

       $Closers = User::whereHas(
           'roles', function($q) {
               $q->where('name', 'closer');
           }
       )->get();

       $closers[] = new User();
       $i = 0;
       foreach($Closers as $Closer) {
           $Messages = $messages->where('closer','=',$Closer->name);
           if(!is_null($Messages)){
               $closers[$i] = $Closer->getStats($Messages);
               $i++;
           }
       }
      
       $allMerchants =  Merchant::OrderBy('id','desc')->get();    
       $notifications = AdminController::getNotificationAdmin() ;
       return view('auth.crbstats', compact('agents', 'closers','messageCount','allMerchants','from','to', 'notifications'));
       
      
   }

    

    public function VerifiedStats() {
        $messages = Message::all();
        $messageCount = $messages->count();

        $currentMonth = date('m');
        $from = null;
        $to = null;
        $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();
    
        $Agents = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
            }
        )->get();

        $agents[] = new User();
        $i = 0;
        foreach($Agents as $Agent) {
            $Messages = $messages->where('userID','=',$Agent->id);
            $agents[$i] = $Agent->getStats($Messages);
            $i++;
        }

        $Closers = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
            }
        )->get();

        $closers[] = new User();
        $i = 0;
        foreach($Closers as $Closer) {
            $Messages = $messages->where('closer','=',$Closer->name);
            $closers[$i] = $Closer->getStats($Messages);
            $i++;
        }
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $notifications = AdminController::getNotificationAdmin() ;
        return view('auth.stats', compact('agents', 'closers', 'messageCount','allMerchants','from','to', 'notifications'));
    }

    public function statsMonthly(Request $input) {
         
        $messages = Message::all() ;
         $messageCount = $messages->count();
         $messages = null;
         $currentDate = explode('/', $input->inputDate);

    //  $messages = Message::whereYear('updated_at', '=', $currentDate[2])->whereMonth('updated_at', '=', $currentDate[0])->get();
        $from = date('Y-m-d', strtotime($input->startDate))." 00:00:00";
        $to = date('Y-m-d', strtotime($input->endDate))." 23:59:59";

        $messages =  Message::whereBetween('updated_at',[$from, $to])->get();
       
        $Agents = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'agent');
            }
        )->get();

        $agents[] = new User();
        $i = 0;
        foreach($Agents as $Agent) {
           
            $Messages = $messages->where('userID','=',$Agent->id);
            if(!is_null($Messages)){
                $agents[$i] = $Agent->getStats($Messages);
                $i++;
            }
        }

        $Closers = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'closer');
            }
        )->get();

        $closers[] = new User();
        $i = 0;
        foreach($Closers as $Closer) {
            $Messages = $messages->where('closer','=',$Closer->name);
            if(!is_null($Messages)){
                $closers[$i] = $Closer->getStats($Messages);
                $i++;
            }
        }
       
        $allMerchants =  Merchant::OrderBy('id','desc')->get();    
        $notifications = AdminController::getNotificationAdmin() ;
        return view('auth.stats', compact('agents', 'closers', 'messageCount','allMerchants','from','to', 'notifications'));
        //return response()->json([$agents,$closers]);
       
    }

    public function userStats($id) {
        $messageCount = Message::count();
        $user = User::find($id);    
        $message = null;
        $Messages = Message::all() ;
        if($user->hasRole('agent')) {
            $messages = $Messages->where('userID','=',$user->id);
        } else {
            $messages = $Messages->where('closer','=',$user->name);
        }
 
        
        $user = $user->getStats($messages);
     
        $allMerchants =  Merchant::OrderBy('id','desc')->get();    
        $notifications = AdminController::getNotificationAdmin() ;
        return view('userStats', compact('messageCount','allMerchants','user', 'notifications'));
       

   }

    public function AllMerchants() {
      $messageCount = Message::count();
      $allMerchants =  Merchant::OrderBy('id','desc')->get();
      return view('allMerchants',compact('allMerchants','messageCount'));
    }

    public function addMerchant(Request $input){
      $user = Merchant::create([
          'name' => $input->name,
          'email' => $input->email,
      ]);
      $notifications = AdminController::getNotificationAdmin() ;
      return redirect('/allMerchants', compact('notifications'))->with('message', 'Merchant has been successfully created');
    }

    public function editMerchant(Request $input){
      $merchant = Merchant::find($input->id);
      $merchant->name = $input->name;
      $merchant->email = $input->email;

      $merchant->save();
      $notifications = AdminController::getNotificationAdmin() ;
      return redirect('/allMerchants', compact('notifications'))->with('message', 'Merchant has been successfully updated');
    }

    public function deleteMerchant($id) {
      $merchant =Merchant::destroy($id);

      return redirect('/allMerchants')->with('message', 'Merchant has been deleted successfully');
    }

    public function callbacks() {
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $messageCount = Message::count();
        
        $types = ["Admin","Agent/Closer"];
        $allCallBacks = RoughCallBack::whereIn('type', $types)->OrderBy('id','desc')->get();
        
        //$currentMonth = date('m');
        // $messages = DB::table("messages")
        //     ->whereRaw('MONTH(created_at) = ?',[$currentMonth])
        //     ->get();
        // $messages = $messages->where('allowcallback','=','yes');
        
        $messages[] = null;
        $i =0;
        
        foreach($allCallBacks as $callBack){
            
            $messages[$i] = Message::where('id',$callBack->message_id)->OrderBy('updated_at','desc')->first();
            
            if($messages[$i] != null){
                $messages[$i]->callBackTime = $callBack->date_time;
                $messages[$i]->type = $callBack->type;
            }
            
            $i++;
            
        }
        $messages = array_filter($messages);
        $notifications = AdminController::getNotificationAdmin() ;
        
        return view('auth.callbacks',compact('messages','messageCount', 'allMerchants', 'notifications'));
    }

    public function rnas() {
        $allMerchants =  Merchant::OrderBy('id','desc')->get();
        $messages = Message::all();
        $messageCount = $messages->count();
        $currentMonth = date('m');
        $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();
        $messages = $messages->where('status','=','RNA');

        foreach ($messages as $message) {
            $message->userName = User::find($message->userID)->name;
            $message->merchant = Merchant::where('id',$message->merchantID)->first();
        }
        
        
        $notifications = AdminController::getNotificationAdmin() ;
        return view('auth.rnas',compact('messages','messageCount', 'allMerchants', 'notifications'));
    }

    public function showSalaryMethod() {
      $messages = Message::all();
      $messageCount = $messages->count();
      $allMerchants =  Merchant::OrderBy('id','desc')->get();  
      $notifications = AdminController::getNotificationAdmin() ;
      return view('auth.salaryMethod',compact('messageCount','allMerchants', 'notifications'));

    }

    public function approvalMethod(Request $input) {
      $messages = Message::all();
      $messageCount = $messages->count();

      $currentMonth = date('m');
      $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();

      $Agents = User::whereHas(
          'roles', function($q) {
              $q->where('name', 'agent');
          }
      )->get();

      $agents[] = new User();
      $i=0;
      foreach($Agents as $Agent) {
        $Messages = $messages->where('userID','=',$Agent->id);
        $agents[$i] = $Agent->getStats($Messages);
        $agents[$i]->basicSalary = $Agent->basicSalary;
        $agents[$i]->totalSalary = $agents[$i]->approvalAmount * ($input->percentage/100) + $agents[$i]->basicSalary;
        $agents[$i]->commission = $agents[$i]->approvalAmount * ($input->percentage/100);
        $i++;
      }

      $Closers = User::whereHas(
          'roles', function($q) {
              $q->where('name', 'closer');
          }
      )->get();

      $closers[] = new User();
      $i=0;
      foreach($Closers as $Closer) {
        $Messages = $messages->where('closer','=',$Closer->name);
        $closers[$i] = $Closer->getStats($Messages);
        $closers[$i]->basicSalary = $Closer->basicSalary;
        $closers[$i]->totalSalary = $closers[$i]->approvalAmount * ($input->percentage/100) + $closers[$i]->basicSalary;
        $closers[$i]->commission = $closers[$i]->approvalAmount * ($input->percentage/100);
        $i++;
      }
      $allMerchants =  Merchant::OrderBy('id','desc')->get();
      $notifications = AdminController::getNotificationAdmin() ;
      return view('auth.salaryStats',compact('agents', 'closers','messageCount','allMerchants', 'notifications'));
    }

    public function submissionMethod(Request $input) {
      $messages = Message::all();
      $messageCount = $messages->count();

      $currentMonth = date('m');
      $messages = Message::whereRaw('MONTH(updated_at) = ?',[$currentMonth])->get();

      $Agents = User::whereHas(
          'roles', function($q) {
              $q->where('name', 'agent');
          }
      )->get();

      $agents[] = new User();
      $i=0;
        foreach($Agents as $Agent) {
            $Messages = $messages->where('userID','=',$Agent->id);
            $agents[$i] = $Agent->getStats($Messages);

            $agents[$i]->commission = 0;
            if($agents[$i]->submissions > $input->rangeMin1 && $agents[$i]->submissions < $input->rangeMax1){
                $agents[$i]->commission = $agents[$i]->submissionAmount * ($input->percentage1/100);
            }
            else if($agents[$i]->submissions > $input->rangeMin2 && $agents[$i]->submissions < $input->rangeMax2){
                $agents[$i]->commission = $agents[$i]->submissionAmount * ($input->percentage2/100);
            }
            else if($agents[$i]->submissions > $input->rangeMin3 && $agents[$i]->submissions < $input->rangeMax3){
                $agents[$i]->commission = $agents[$i]->submissionAmount * ($input->percentage3/100);
            }
            else if($agents[$i]->submissions > $input->rangeMin4 && $agents[$i]->submissions < $input->rangeMax4){
                $agents[$i]->commission = $agents[$i]->submissionAmount * ($input->percentage4/100);
            }
            else{
                $agents[$i]->commission = $agents[$i]->submissionAmount * ($input->percentage5/100);
            }
            $agents[$i]->basicSalary = $Agent->basicSalary;
            $agents[$i]->totalSalary = $agents[$i]->approvalAmount * ($input->percentage/100) + $agents[$i]->basicSalary;
            $i++;
      }

      $Closers = User::whereHas(
          'roles', function($q) {
              $q->where('name', 'closer');
          }
      )->get();

      $closers[] = new User();
      $i=0;
      foreach($Closers as $Closer) {
        $Messages = $messages->where('closer','=',$Closer->name);
        $closers[$i] = $Closer->getStats($Messages);

        $closers[$i]->commission = 0;
        if($closers[$i]->submissions > $input->rangeMin1 && $closers[$i]->submissions < $input->rangeMax1){
            $closers[$i]->commission = $closers[$i]->submissionAmount * ($input->percentage1/100);
        }
        else if($closers[$i]->submissions > $input->rangeMin2 && $closers[$i]->submissions < $input->rangeMax2){
            $closers[$i]->commission = $closers[$i]->submissionAmount * ($input->percentage2/100);
        }
        else{
            $closers[$i]->commission = $closers[$i]->submissionAmount * ($input->percentage3/100);
        }
        $closers[$i]->basicSalary = $Closer->basicSalary;
        $closers[$i]->totalSalary = $closers[$i]->approvalAmount * ($input->percentage/100) + $closers[$i]->basicSalary;
        $i++;
      }
      
      $allMerchants =  Merchant::OrderBy('id','desc')->get();    
      $notifications = AdminController::getNotificationAdmin() ;
      return view('auth.salaryStats',compact('agents', 'closers','messageCount','allMerchants', 'notifications'));
    }

    public function registerTeam (Request $input){
      $team = new Team();
      $team->name = $input->name;
      $team->save();
      return redirect()->back()->with('message', 'Team has been successfully added');
    }
	
	public function sendMail (Request $input){
			 
		
		
	 $data = array('customerName'=>$input->customerName,'messageBody'=>$input->messageBody,'fees'=>$input->fees);
   
      Mail::send('emails.message', $data, function($message) use($input){
         $message->to($input->merchantEmail, 'Tutorials Point')->subject
            ('Message Notification');
         $message->from('shahvaizahmed29@gmail.com','Alpha Marketing');
      });
      return redirect()->back()->with('message','Email Send Successfully');
    }
	
}
