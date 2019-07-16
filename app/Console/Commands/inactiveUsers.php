<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB ;
use App\User ;
use Pusher\Pusher ;

class inactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inactiveUsers:bsdk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies the admin for inactive status of agent everyday at 20:45';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('users')
                    ->select('id', 'name')
                    ->where('active', '=', '0')
                    ->get()
                    ->toArray() ;
                    
        $pusher = inactiveUsers::getPusher() ;
        $pusher->trigger('isOnline', 'BSDK', $users) ;
    }

    public function getPusher() {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
          );
          $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
          );

        return $pusher ;
    }
}
