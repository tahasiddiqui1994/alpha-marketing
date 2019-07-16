<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }

    public function username() {
        return 'name';
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['disabled' => 0,'del' => 0,'active' => 0]);
    }

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if ($request->ajax()){

            return response()->json([
                'auth' => auth()->check(),
                'user' => $user,
                'intended' => $this->redirectPath(),
            ]);

        }
    }

}
