<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Role;
use App\RoughCallBack;
use Mail;
use Auth;
use DB;
use Hash;
use Session;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null) {
        $role =  Auth::user()->roles->first()->name;
        if($role == 'agent') {
             if(!is_null($id)){
                $message = Message::find($id);
                if($message->userID != Auth::user()->id || $message->status != "RoughCall"){
                    return redirect('/404');
                } 
                return redirect('/home')->with('messageID',$message->id)
                                        ->with('customerName',$message->customername)
                                       ->with('text',$message->text)
                                       ->with('contactNo',$message->contactNo);
            }
            $closers = User::whereHas(
                'roles', function($q) {
                    $q->where('name', 'closer');
                }
            )->where('disabled','=',0)->get();

            $agentID = Auth::guard('web')->user()->id;
            return view('agenthome',compact('closers','agentID'));
        } else {
            if(!is_null($id)){
                $messages = Message::where('closer','=',Auth::user()->name)->where('id','=',$id)->get();
            }else{
                $messages = Message::where('closer','=',Auth::user()->name)->get();
            }

            foreach($messages as $message) {
                $message->callBackType = 'none';
                $message->callBackTime = '-----';
                if(!is_null(RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->first())){
                    $PreCallBack = RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->first();
                    $message->callBackType = $PreCallBack->type;
                    $message->callBackTime = $PreCallBack->date_time; 
                }     
                $message->username = User::find($message->userID)->name;
            }

            return view('closerhome',compact('messages'));
        }
    }

    public function SaveMessage(Request $input) {
      $ID = Auth::guard('web')->user()->id;

      $newMessage = new Message();
      $newMessage->text = $input->message;
      $newMessage->userID = $ID;
      $newMessage->closer = $input->closer;
      $newMessage->customername = $input->customername;
      $newMessage->status = $input->status;
      $newMessage->fees = $input->fee;
      $newMessage->allowcallback = 'none';
      $newMessage->contactNo = $input->contactNo;    
      $newMessage->save();

      return redirect()->back()->with('message', 'Your message has been successfully recieved');

    }

    public function agentMessageSave(Request $input){

      $ID = $input->agentID;
      if($input->messageID == '-1'){
        $newMessage = new Message();
        }else{
            $newMessage = Message::find($input->messageID);
        }

      $newMessage->text = $input->message;
      $newMessage->userID = $ID;
      $newMessage->closer = $input->closername;
      $newMessage->customername = $input->customername;
      $newMessage->status = 'Attempt';
      $newMessage->allowcallback = 'none';
      $newMessage->contactNo = $input->contactNo;    
      $message = $newMessage;

      $sessionKey = $input->session()->get('key');

      

      if (Auth::user()->roles->first()->name == 'closer' ) {
        $newMessage->status = 'Transfer';
        $userName = User::where('id','=',$ID)->first()->name;
        $newMessage->userName = $userName;
        $message = $newMessage;
		$message->transferTime = $input->session()->get('transferTime');
        return view('closerTransfer',compact('message'));
      }
      else{

        $closer = User::where('name',$input->closername)->first();

        if(Hash::check($input->password, $closer->password)){
            
            $input->session()->forget($sessionKey);    
            Auth::logout();
        
            if(Auth::guard('web')->attempt(['name' => $input->closername, 'password' => $input->password], $input->_token)){
           
            // $newMessage->save();
            $newMessage->status = 'Transfer';
            $userName = User::where('id','=',$ID)->first()->name;
            $message->userName = $userName;
			$transferTime = time();
			$input->session()->put('transferTime', $transferTime);
            $message->transferTime = $transferTime;
            return view('closerTransfer',compact('message'));

            }

        }
        
        else {
          return redirect()->back()->with('message', 'Closer Invalid Password')
                                    ->with('messageID',$input->messageID)
                                   ->with('customerName',$input->customername)
                                   ->with('text',$input->message)
                                   ->with('contactNo',$input->contactNo);
        }

      }

    }

    public function agentSubmitDMP(Request $input){
      $ID = $input->agentID;

      if($input->messageID == '-1'){
            $newMessage = new Message();
        }else{
            $newMessage = Message::find($input->messageID);
        }

      $newMessage->text = $input->message;
      $newMessage->userID = $ID;
      $newMessage->closer = "None";
      $newMessage->customername = $input->customername;
      $newMessage->status = $input->status;
      $newMessage->fees = 0;
      $newMessage->allowcallback = 'none';
      $newMessage->contactNo = $input->contactNo;
     
      
      if (Auth::user()->roles->first()->name == 'closer' ) {

          $newMessage->status = 'Transfer';
          $userName = User::where('id','=',$ID)->first()->name;
          $newMessage->userName = $userName;
          $message = $newMessage;
          return view('closerTransfer',compact('message'))->with('sessionMessage', 'Unauthenticated user action');
      }
      else{
        $newMessage->updatedBy = $ID;  
        $newMessage->save();
        return redirect('/home')->with('message', 'Your DMP has been successfully recieved');
      }


    }

    public function agentRoughCallBack(Request $input){
      $ID = $input->agentID;

      if($input->messageID == '-1'){
        $newMessage = new Message();
    }else{
        $newMessage = Message::find($input->messageID);
    }

      $newMessage->text = $input->message;
      $newMessage->userID = $ID;
      $newMessage->closer = "None";
      $newMessage->customername = $input->customername;
      $newMessage->status = 'RoughCall';
      $newMessage->fees = 0;
      $newMessage->allowcallback = 'none';
      $newMessage->contactNo = $input->contactNo;
      
      //$message = DB::table('messages')->orderBy('id', 'desc')->first();

      $dateTime = date_create($input->Date);
      $dateTime = date_format($dateTime,"Y/m/d H:i:s");

      $callBack = new RoughCallBack();
      $callBack->date_time = $dateTime;
      $callBack->type= "Agent";

      if (Auth::user()->roles->first()->name == 'closer' ) {

        $newMessage->status = 'Transfer';
        $userName = User::where('id','=',$ID)->first()->name;
        $newMessage->userName = $userName;
        $message = $newMessage;
        return view('closerTransfer',compact('message'))->with('sessionMessage', 'Unauthenticated user action');
      }
      else{
        $newMessage->updatedBy = $ID;   
        $newMessage->save();
        $callBack->message_id = $newMessage->id;
        $callBack->save();

        return redirect('/home')->with('message', 'Your RoughCall has been successfully recorded');
      }


    }

    public function CloserMessageSave(Request $input){

        if($input->messageID == '-1') {
            $message = new Message();
        }else{
            $message = Message::find($input->messageID);
        }
        $message->text = $input->message.'agentMessageEnd'.$input->agentmessage;
        $message->customername = $input->customername;
        $message->userID = $input->agentID;
        $message->status = $input->status;
        $message->allowcallback = 'none';
        $message->closer = $input->closername;
        $message->fees = $input->fees;
        $message->contactNo = $input->contactNo;
        
        if (Auth::user()->roles->first()->name == 'agent' ) {
          return redirect('/home')->with('message', 'Unauthenticated user action')
                                    ->with('messageID',$input->messageID)
                                   ->with('customerName',$input->customername)
                                   ->with('text',$input->message)
                                   ->with('contactNo',$input->contactNo);
        }
        else{

          if((($input->transferTime) + 180) > time()){
            $message->unAttempt = 1;
          }

          
             $message->save();
          if($input->status == "CallBack") {
            $callBack = new RoughCallBack();
            $callBack->message_id = $message->id;
            
            $dateTime = date_create($input->Date);
            $dateTime = date_format($dateTime,"Y/m/d H:i:s");

            $callBack->date_time = $dateTime;
            $callBack->type= "Agent/Closer";
            $callBack->save();
            $message->allowcallback = 'agent/closer';
          }
          $message->updatedBy = Auth::guard('web')->user()->id;
          $message->save();
          Auth::logout();    
          return redirect('/home')->with('message','Your message has been successfully recieved');
      }

    }

    public function agentTransfer(Request $input){

        if($input->messageID == '-1'){
            $message = new Message();
        }else{
            $message = Message::find($input->messageID);
        }
        $message->text = $input->message;
        $message->customername = $input->customername;
        $message->userID = $input->agentID;
        $message->status = 'Attempt';
        $message->allowcallback = 'none';
        $message->closer = $input->closername;
        $message->contactNo = $input->contactNo;
        $sessionKey = $input->session()->get('key');
       

        if (Auth::user()->roles->first()->name == 'agent' ) {
          return redirect('/home')->with('message', 'Unauthenticated user action')
                                   ->with('messageID',$input->messageID)
                                   ->with('customerName',$input->customername)
                                   ->with('text',$input->message)
                                   ->with('contactNo',$input->contactNo);
        }
        else{

            $agent = User::where('name',$input->name)->first();

            if(Hash::check($input->password, $agent->password)){
            
            $input->session()->forget($sessionKey);    
            Auth::logout();
          
                if(Auth::guard('web')->attempt(['name' => $input->name, 'password' => $input->password])){


                    $role =  Auth::user()->roles->first()->name;
                    if($role == 'agent') {
                            $closers = User::whereHas(
                                'roles', function($q) {
                                    $q->where('name', 'closer');
                                }
                            )->where('disabled','=',0)->get();

                    }
                   

                    return redirect('/home')->with('messageID',$message->id)
                                        ->with('customerName',$message->customername)
                                       ->with('text',$message->text)
                                       ->with('contactNo',$message->contactNo);
                    //return view('agentTransfer',compact('message','closers'));
                }
               
            }

            else{

                $userName = User::where('id','=',$message->userID)->first()->name;
                $message->userName =   $userName;

                return view('closerTransfer',compact('message'))->with('sessionMessage', 'Invalid User Password');
            }

        }

  }

    public function history($id = null) {
        $role =  Auth::user()->roles->first()->name;
        if($role == 'agent') {
            $ID = Auth::guard('web')->user()->id;
            if(!is_null($id)){
                $messages = Message::where('userID','=',$ID)->where('id','=',$id)->orderBy('updated_at','desc')->get();
                
            }else{
                $messages = Message::where('userID','=',$ID)->orderBy('updated_at','desc')->get();
            }
            
            foreach($messages as $message){
                $message->callBackType = 'none';
                $message->callBackTime = '-----';
                if(!is_null(RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->first())){
                    $PreCallBack = RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->first();
                    $message->callBackType = $PreCallBack->type;
                    $message->callBackTime = $PreCallBack->date_time;    
                }     
            }
            
            $username = Auth::guard('web')->user()->name;

            return view('history', compact('messages','username'));
        } else {
            $messages = Message::where('closer','=',Auth::user()->name)->orderBy('id', 'DESC')->get();

            foreach($messages as $message) {
                $messages->username = User::find($message->userID)->name;
                
                 $message->callBackType = 'none';
                 $message->callBackTime = '-----';
                if(!is_null(RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->get())){
                    $PreCallBack = RoughCallBack::where('message_id',$message->id)->OrderBy('updated_at','DESC')->first();
                    $message->callBackType = $PreCallBack->type;
                    $message->callBackTime = $PreCallBack->date_time; 
                }     
            }
            return view('closerhome',compact('messages'));
        }
    }

    public function editMessage($id) {
        $message = Message::find($id);

        return view('agent.editmessage',compact('message'));
    }

    public function updateMessage(Request $input) {
        $ID = Auth::guard('web')->user()->id;
        
        $user = User::where('id',$ID)->first();

        if(!Hash::check($input->password, $user->password)){
             return redirect()->back()->with('message', 'Invalid Password');
        }
        
        else{
            
            $newMessage = Message::find($input->id);
            $newMessage->text = $input->message;
            $newMessage->closer = $input->closer;
            $newMessage->customername = $input->customername;
            $newMessage->note = $input->note;
            $newMessage->fees = $input->fee;
            $newMessage->contactNo = $input->contactNo;
            $message->allowcallback = "none";
            
            if($input->status == "CallBack" || $input->status == "RoughCall") {
                
                $callBack = new RoughCallBack();
                $callBack->message_id = $newMessage->id;
        
                $dateTime = date_create($input->Date);
                $dateTime = date_format($dateTime,"Y/m/d H:i:s");
        
                $callBack->date_time = $dateTime;
               
                $callBack->type= "Agent/Closer";
                
                if($input->status == 'BankAuth CallBack'){
                    $callBack->type= "Admin";
                }
                
                $callBack->save();
            }
            
             if($newMessage->status == "Dropped" || $newMessage->status == "Decline" || $newMessage->status == "RNA" || $newMessage->status == "Still RNA" || $newMessage->status == "BankAuth" || $newMessage->status == "Still BankAuth"){
                 if($input->status != $newMessage->status){
                    
                    $callBack = new RoughCallBack();
                    $callBack->message_id = $newMessage->id;
                
                    $callBack->date_time = date('Y-m-d h:i:sa');
                   
                    $callBack->type= "Admin";
                    
                    $callBack->save();
                    
                  
                 }
             }
            
            $newMessage->status = $input->status;  
            $newMessage->updatedBy = Auth::guard('web')->user()->id;
            $newMessage->save();

            return redirect('/home')->with('message', 'Your message has been successfully updated');
        }
    }

    public function getRecentMessages() {
        $recentMessage = Message::whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->get();
        foreach($recentMessage as $message) {
            $message->userName = User::find($message->userID)->name;
        }
        return response()->json($message);
    }
    
    public function getNotification($userType,$userIdentity){
		//echo $userType . $userIdentity;
		If($userType == '1'){
			$query = "select max(r.id) as 'main_id',r.date_time,r.type,r.seen, m.* from messages as m join roughcallbacks as r on m.id = r.message_id where r.date_time > DATE_SUB(curdate(), INTERVAL 1 DAY) and m.userID = '$userIdentity' and r.type like '%Agent%' and r.seen = 0 and (m.status = 'RoughCall' or m.status = 'CallBack' or m.status = 'RNA' or m.status = 'BankAuth' ) GROUP by id ORDER by date_time DESC";
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
    
    public function updateStatus(Request $input){
          
        $message = Message::find($input->messageID);
        $agent_closer = User::where('id',$input->ID)->first();
        $message->allowcallback = "none";
        if(Hash::check($input->password, $agent_closer->password)){
           
                
            if( ($message->status == 'CallBack') && ($message->status == $input->status) ){
               
                $PreCallBack = RoughCallBack::where('message_id',$input->messageID)->OrderBy('updated_at','DESC')->first();    
              
                $dateTime = date_create($input->Date);
                $dateTime = date_format($dateTime,"Y/m/d H:i:s");
                $PreCallBack->date_time = $dateTime;
                $PreCallBack->type = 'Agent/Closer';    
                
                $PreCallBack->save();
            }
            
            if($input->status == 'CallBack') {
                
                $callBack = new RoughCallBack();
                $callBack->message_id = $message->id;
    
                $dateTime = date_create($input->Date);
                $dateTime = date_format($dateTime,"Y/m/d H:i:s");
    
                $callBack->date_time = $dateTime;
                $callBack->type= 'Agent/Closer';
                $callBack->save();
             
            }
            
             if($message->status == "Dropped" || $message->status == "Decline" || $message->status == "RNA" || $message->status == "Still RNA" || $message->status == "BankAuth" || $message->status == "Still BankAuth"){
                 if($input->status != $message->status){
                    
                    $callBack = new RoughCallBack();
                    $callBack->message_id = $message->id;
                
                    $callBack->date_time = date('Y-m-d h:i:sa');
                   
                    $callBack->type= "Admin";
                    
                    $callBack->save();
                    
                  
                 }
             }
            
            $message->updatedBy = Auth::guard('web')->user()->id;
            $message->status = $input->status;
            $message->save();
            return redirect()->back()->with('message', 'Your message has been successfully updated');        
        }
        else{
            
            return redirect()->back()->with('message', 'Invalid Password');
        }
       
        
    }
}
