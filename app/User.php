<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Message;
use App\User ;
use DB ;
use \stdClass ;

class User extends Authenticatable
{
    use Notifiable;

    private $messages;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($password) {
        $this->attributes['password'] = \Hash::make($password);
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole(Role $role) {
        return $this->roles()->save($role);
    }

    public function authorizeRoles($roles) {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                    abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
                abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles) {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    public function hasRole($role) {
        return null !== $this->roles()->where('name', $role)->first();
    }

    //STATS
    private function getTransfers() {

        if($this->hasRole('agent')) {
           return $this->messages->where('status','!=','CR Approved')->where('status','!=','Attempt')->where('status','!=','DMP')->where('status','!=','DMP Approved')->where('status','!=','Submit')->count();
        }
        else{
          return $this->messages->where('status','!=','CR Approved')->where('status','!=','Attempt')->where('status','!=','DMP')->where('status','!=','DMP Approved')->where('status','!=','Submit')->where('unAttempt','!=', 1)->count();
        }

    }

    private function getCallbacks() {
        return $this->messages->where('status','=','Callback')->count();
    }

    private function getApprovals() {
        return $this->messages->filter(function($message) {
                    return strstr($message->status, 'Approved') ||
                            strstr($message->status, 'Chargeback');
                })->count();
    }

    private function getSubmissions() {
        return $this->messages->filter(function($message) {
                    return strstr($message->status, 'Submit') ||
                            strstr($message->status, 'Approved')||
                            strstr($message->status, 'Decline') ||
                            strstr($message->status, 'Chargeback');
                })->count();
    }

    private function getunAttempts() {
       
        return $this->messages->where('unAttempt','=',1)->count();
    }
    
    private function getUnAttemptsCallBack() {
        return $this->messages->where('unAttempt','=',1)->where('prevStatus', '=', 'Callback')->count();
    }
    
    private function getUnAttemptsDropped() {
        return $this->messages->where('unAttempt','=',1)->where('prevStatus', '=', 'Dropped')->count();
    }
    
    private function getAttempts() {
        return $this->messages->where('unAttempt','=',0)->count();
    }
    
    private function getAttemptsCallBack() {
        return $this->messages->where('unAttempt','=',0)->where('prevStatus', '=', 'Callback')->count();
    }
    
    private function getAttemptsDropped() {
        return $this->messages->where('unAttempt','=',0)->where('prevStatus', '=', 'Dropped')->count();
    }
    
    private function getAttemptsVerified() {
        return $this->messages->where('unAttempt','=',0)->where('prevStatus', '=', 'Verified')->count();
    }

    private function getSubmissionAmount() {
        return $this->messages->filter(function($message) {
                    return strstr($message->status, 'Submit') ||
                            strstr($message->status, 'Approved')||
                            strstr($message->status, 'Chargeback');
                })->sum('fees');
    }

    private function getApprovalAmount() {
        return $this->messages->where('status','=','Approved')->sum('finalFee');
    }

    private function getRatio() {
        if($this->getSubmissionAmount() != 0){
        return ($this->getApprovalAmount()/$this->getSubmissionAmount())*100;
      }
      else{
        return 0;
      }

    }

    public function getFees(){
        return $this->messages->where('prevStatus','=','Verified')->sum('fees') ;
    }

    public function getCrFee() {
        return $this->messages->where('prevStatus', '=', 'CRB')->sum('fees') ;
    }

    public function getApproved(){
        //dd($this->messages->where('prevStatus','=', 'Verified')->where('status','=', 'Approved')->count());
        return $this->messages->where('prevStatus','=', 'Verified')->where('status','=', 'Approved')->count() ;
    }

    public function getDMP() {
        return $this->messages->where('prevStatus','=', 'DMP')->count() ;
    }
    public function getDMPRNA() {
        return $this->messages->where('prevStatus','=', 'DMP')->where('status','=', 'RNA')->count() ;
    }
    public function getDMPCallback() {
        return $this->messages->where('prevStatus','=', 'DMP')->where('status','=', 'CallBack')->count() ;
    }
    public function getDMPStillRNA() {
        return $this->messages->where('prevStatus','=', 'DMP')->where('status','=', 'Still RNA')->count() ;
    }
    public function getDMPDecline() {
        return $this->messages->where('prevStatus','=', 'DMP')->where('status','=', 'Decline')->count() ;
    }
    public function getDMPApproved() {
        return $this->messages->where('prevStatus','=', 'DMP')->where('status','=', 'Approved')->count() ;
    }
    

    public function getCRB() {
    
        return $this->messages->where('closer','!=', 'None')->where('prevStatus','=', 'CRB')->count() ;
    }
    public function getCRBRNA() {
        return $this->messages->where('prevStatus','=', 'CRB')->where('status','=', 'RNA')->count() ;
    }
    public function getCRBCallback() {
        return $this->messages->where('prevStatus','=', 'CRB')->where('status','=', 'CallBack')->count() ;
    }
    public function getCRBStillRNA() {
        return $this->messages->where('prevStatus','=', 'CRB')->where('status','=', 'Still RNA')->count() ;
    }
    public function getCRBDecline() {
        return $this->messages->where('prevStatus','=', 'CRB')->where('status','=', 'Decline')->count() ;
    }
    public function getCRBApproved() {
        return $this->messages->where('prevStatus','=', 'CRB')->where('status','=', 'Approved')->count() ;
    }

    public function getcrvrApproved() {
        return ($this->messages->where('prevStatus','=', 'Verified')->where('status','=', 'Approved')->sum('finalFee')+$this->messages->where('prevStatus','=', 'CRB')->where('status','=', 'Approved')->sum('finalFee')) ;
    }
    
    public function getCloserCRB() {
        //echo $this->messages->where('prevStatus','=', 'CRB')->where('closer','!=', 'None')->count(), '\n' ;
        return $this->messages->where('prevStatus','=', 'CRB')->where('closer','!=', 'None')->count() ;
    }
    public function getCloserCRBAgentID($name) {
       
                
        // $totalCRBsByUser = DB::table('messages') 
        //     ->join('users', 'users.id', '=', 'messages.userID')
        //     ->select('messages.userID as id', 'users.name', DB::raw('count(*) as total'))
        //     ->where('prevStatus','=', 'CRB')->where('closer',$name)
        //     ->groupBy('userID')->get()->toArray();
            $totalCRBsByUser = $this->messages->where('prevStatus','=', 'CRB')->where('closer',$name)->groupBy('userID');
        
            foreach($totalCRBsByUser as $index => $item){
               
                $totalCRBsByUser[$index]->count = $item->count();
                $totalCRBsByUser[$index]->agent = User::where('id',$index)->first();
               
            }

        return $totalCRBsByUser;   
     
    }
    public function getApprovedAmount(){

        return $this->messages->where('prevStatus','=', 'Verified')->where('status','=', 'Approved')->get('finalFee') ;
    }
    public function getReturnAmount(){

        return $this->messages->where('prevStatus','=', 'Verified')->where('status','=', 'Approved')->get('returnAmount') ;
    }
    // public function getApproved(){
    //     //dd($this->messages->where('prevStatus','=', 'Verified')->where('status','=', 'Approved')->count());
    //     return $this->messages->where('prevStatus','=', 'Verified')->where('status','=', 'Approved')->count() ;
    // }

   
    
    public function getCRBByUsers() {
        $user_info = DB::table('messages')
                ->select('userID', DB::raw('count(*) as total'))
                ->where('prevStatus','=', 'CRB')->where('closer','!=', 'None')
                ->groupBy('userID')
                ->get();
        
       
        return $user_info ;
    }

    public function getStats($Messages) {
        $user = new \stdClass();
        $user->id = $this->id;
        $user->name = $this->name;
        $user->showpass = $this->showpass;

        if($this->hasRole('agent')) {
            $this->messages = $Messages->where('userID','=',$this->id);
        } else {
            $this->messages = $Messages->where('closer','=',$this->name);
        }

        $user->transfers = $this->getTransfers();
        $user->allowcallback = $this->getCallbacks();
        $user->submissions = $this->getSubmissions();
        $user->approvals = $this->getApprovals();
        $user->unattempts = $this->getunAttempts();
        $user->unattemptscallback = (int)$this->getUnAttemptsCallBack();
        $user->unattemptsdropped = (int)$this->getUnAttemptsDropped();
        $user->attempts = $this->getAttempts();
        $user->attemptscallback = (int)$this->getAttemptsCallBack();
        $user->attemptsdropped = (int)$this->getAttemptsDropped();
        $user->attemptsverified = (int)$this->getAttemptsVerified();
        $user->submissionAmount = $this->getSubmissionAmount();
        $user->approvalAmount = $this->getApprovalAmount();
        $user->ratio = $this->getRatio() . '%';
        $user->fees = $this->getFees() ;
        $user->approved = $this->getApproved() ;
        $user->approvedAmount = $this->getApprovedAmount() ;
        $user->returnAmount = $this->getReturnAmount() ;

        $user->dmp = $this->getDMP() ;
        $user->dmprna = $this->getDMPRNA() ;
        $user->dmpstillrna = $this->getDMPStillRNA() ;
        $user->dmpdecine = $this->getDMPDecline() ;
        $user->dmpapproved = $this->getDMPApproved() ;
        $user->dmpcallback = $this->getDMPCallback() ;

        
        $user->crb = $this->getCRB() ;
        $user->crbrna = $this->getCRBRNA() ;
        $user->crbstillrna = $this->getCRBStillRNA() ;
        $user->crbdecine = $this->getCRBDecline() ;
        $user->crbapproved = $this->getCRBApproved() ;
        $user->crbcallback = $this->getCRBCallback() ;

        $user->crvr = $user->crb+$user->attemptsverified ;
        $user->totalcrvr = $this->getCrFee()+$this->getFees() ;
        $user->totalcrvrapproved = $this->getcrvrApproved() ;

        $user->CRBTC = $this->getCloserCRB() ;
        //$user->getCRBByUsers = $this->getCRBByUsers() ;
        //$user->agentNames =  array() ;
        $user->CRBTCAID =null;
        if($this->hasRole('closer')) {
            $user->CRBTCAID =  $this->getCloserCRBAgentID($user->name) ;
        }
        

        return $user;
    }
}
