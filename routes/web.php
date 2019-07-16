<?php

use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use App\Mail\MyMail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::guest())
        return view('auth.login');
    else
        return redirect('/home');
});

Route::get('/getCount', function() {
    $data[] = null;
    $data[0] = Message::count();
    $data[1] = Message::select('id','closer','customername','fees','status','userID')->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','desc')->first();
    $data[1] = $data[1]->status;
    return $data;
});

Auth::routes();

Route::post('/saveMessage', 'HomeController@SaveMessage')->name('saveMessage');
Route::get('/editMessage/{id}','HomeController@editMessage');
Route::get('/getNotification/{userType}/{userIdentity}','HomeController@getNotification');
Route::get('/getNotificationAdmin/{userType}/{userIdentity}','AdminController@getNotification');
Route::post('/updateMessage','HomeController@updateMessage');

Route::group(['middleware' => 'web'], function () {
  Route::post('/agentMessageSave', 'HomeController@agentMessageSave')->name('agentMessageSave');
  Route::post('/agentSubmitDMP','HomeController@agentSubmitDMP')->name('agentSubmitDMP');
  Route::post('/agentSubmitCRB','HomeController@agentSubmitCRB')->name('agentSubmitCRB');
  Route::post('/agentRoughCallBack','HomeController@agentRoughCallBack')->name('agentRoughCallBack');
  Route::post('/CloserMessageSave','HomeController@CloserMessageSave')->name('CloserMessageSave');
  Route::post('/agentTransfered','HomeController@agentTransfer')->name('agentTransfer');
});


Route::get('/history/{id?}','HomeController@history')->name('history');
Route::get('/home/{id?}', 'HomeController@index');
Route::get('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');
Route::post('/updateStatus','HomeController@updateStatus')->name('updateStatus');

Route::get('/allUsers','AdminController@AllUser');
Route::get('/allAdmins','AdminController@AllAdmin');
Route::get('/allTeams','AdminController@AllTeam');
Route::get('/allMessages','AdminController@AllMessages');
Route::post('/messageMonthly','AdminController@messageMonthly')->name('messageMonthly');
Route::post('/statsMonthly', 'AdminController@statsMonthly')->name('statsMonthly');
Route::get('/allMerchants','AdminController@AllMerchants');
Route::get('/userStats/{id}', 'AdminController@userStats');


// ADMIN Routes
Route::prefix('admin')->group(function() {
  Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
  Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
  Route::get('/', 'AdminController@index')->name('admin.dashboard');
  Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

  Route::get('/changePass', function() {
    return view('auth.changepass');
  })->name('changePass');
  Route::post('/updatePass', 'AdminController@updatePass')->name('updatePass');

  Route::get('/register', 'AdminController@showRegistrationForm')->name('register');
  Route::post('/register', 'Auth\RegisterController@register');
  Route::get('/registerAdmin', 'AdminController@showRegistrationFormAdmin');
  Route::post('/insertAdmin', 'AdminController@insertAdmin')->name('insertAdmin');
  Route::get('/addUser', 'AdminController@addUser')->name('addUser');
  Route::get('/viewUser/{id}', 'AdminController@viewUser')->name('viewUser');
  Route::post('/insertUser', 'AdminController@insertUser')->name('insertUser');
  Route::get('/editUser/{id}', 'AdminController@edituser')->name('edituser');
  Route::get('/editAdmin/{id}', 'AdminController@editadmin')->name('editadmin');
  Route::post('/updateUser', 'AdminController@updateUser')->name('updateUser');
  Route::post('/updateAdmin', 'AdminController@updateAdmin')->name('updateAdmin');
  Route::get('/disableUser/{id}', 'AdminController@disableUser')->name('disableUser');
  Route::get('/disableAdmin/{id}', 'AdminController@disableAdmin')->name('disableAdmin');
  Route::get('/deleteUser/{id}', 'AdminController@deleteUser')->name('deleteUser');
  Route::post('/deleteAdmin', 'AdminController@deleteAdmin')->name('deleteAdmin');
  Route::get('/enableUser/{id}', 'AdminController@enableUser')->name('enableUser');
  Route::get('/enableAdmin/{id}', 'AdminController@enableAdmin')->name('enableAdmin');
  Route::post('/ActiveToggle','AdminController@ActiveToggle')->name('ActiveToggle');
  Route::post('/ActiveToggleAdmin','AdminController@ActiveToggleAdmin')->name('ActiveToggleAdmin');
  Route::post('/registerTeam', 'AdminController@registerTeam')->name('registerTeam');

  Route::get('/getRecentMessages', 'AdminController@getRecentMessages')->name('getRecentMessages');
  Route::get('/alertMessage', function() {
    event(new MessageCreatedTrigger());
  });

  Route::post('/deleteMessage', 'AdminController@deleteMessage');
  Route::get('/showMessage/{id}', 'AdminController@showMessage');
  Route::get('/editMessage/{id}', 'AdminController@editMessage');
  Route::post('/updateMessage', 'AdminController@updateMessage')->name('updateMessage');


  Route::get('/showSalaryMethod', 'AdminController@showSalaryMethod')->name('salaryMethods');
  Route::get('/DMPStats', 'AdminController@DMPStats')->name('DMPStats');
  Route::post('/DMPStatsMonthly', 'AdminController@DMPStatsMonthly')->name('DMPStatsMonthly');
  Route::get('/VerifiedStats', 'AdminController@VerifiedStats')->name('VerifiedStats');
  Route::get('/CRBStats', 'AdminController@CRBStats')->name('CRBStats');
  Route::post('/CRBStatsMonthly', 'AdminController@CRBStatsMonthly')->name('CRBStatsMonthly');
  Route::get('/AmountStats', 'AdminController@AmountStats')->name('AmountStats');
  Route::post('/AmountStatsMonthly', 'AdminController@AmountStatsMonthly')->name('AmountStatsMonthly');
  Route::get('/callbacks', 'AdminController@callbacks')->name('callbacks');
  Route::get('/rnas', 'AdminController@rnas')->name('rnas');

  //Salary Method Routes
  Route::post('/approvalMethod','AdminController@approvalMethod')->name('salaryMethodApproval');
  Route::post('/submissionMethod','AdminController@submissionMethod')->name('salaryMethodSubmission');

  // Merchant Routes
  Route::post('addMerchant', 'AdminController@addMerchant')->name('AddMerchant');
  Route::post('editMerchant', 'AdminController@editMerchant')->name('UpdateMerchant');
  Route::get('/deleteMerchant/{id}', 'AdminController@deleteMerchant')->name('deleteMerchant');

  // Password reset routes
  Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
  Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
  Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset');
  Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');

  Route::get('getNotificationAdmin', 'AdminController@getNotificationAdmin')->name('getNotificationAdmin') ;
  //Mailable Routes
   Route::post('/sendMail' , 'AdminController@sendMail')->name('sendMail');
});






Route::get('/404', function () {
    return view('404');
})->name('404');
