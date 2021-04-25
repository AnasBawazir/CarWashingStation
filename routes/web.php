<?php

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

Auth::routes();



Route::get('/', 'Auth\LoginController@showLoginForm');
Route::post('/register', 'Auth\RegisterController@create');
Route::get('/magasil/register', 'Employee\EmployeeController@show_register');

//Route::get('/magasil/register',function (){
//    return view('auth.register');
//});


// Admin
Route::middleware(['auth'])->prefix('admin')->group(function ()
{
    Route::resources([
        'stations' => 'Admin\StationController',
        'service' => 'Admin\ServiceController',
        'category' => 'Admin\CategoryController',
        'coworkers' => 'Admin\CoworkersController',
        'offer' => 'Admin\OfferController',
        'notification_template' => 'Admin\NotificationTemplateController',
        'faq' => 'Admin\FaqController',
        'appointment' => 'Admin\AppointmentController',
        'user' => 'Admin\UserController',
        'language' => 'Admin\LanguageController',
        'role' => 'Admin\RoleController',
    ]);

    Route::get('appointment_invoice/{id}','Admin\AppointmentController@appointment_invoice');
    Route::get('invoice_print/{id}','Admin\AppointmentController@invoice_print');

    Route::get('appointmentChart','Admin\HomeController@AppointmentChart');
    Route::get('userChart','Admin\HomeController@userChart');

    Route::post('timeslots','Admin\AppointmentController@timeslots');
    Route::get('calendar','Admin\AdminController@calendar');

    Route::get('change_language/{name}','Admin\AdminController@change_language');
    Route::get('calendarData/{id}','Admin\AdminController@calendarData');

    Route::post('appointment_service','Admin\AdminController@appointment_service');
    Route::get('notification','Admin\AdminController@notification');

    Route::post('send_notification','Admin\AdminController@send_notification');
    Route::get('admin_edit','Admin\AdminController@edit');
    Route::get('setting','Admin\SettingController@setting');

    Route::post('update_admin_profile','Admin\AdminController@update_admin_profile');
    Route::post('update_setting','Admin\SettingController@update_setting');

    Route::post('update_payment_setting','Admin\AdminController@update_payment_setting');
    Route::post('update_privacy_policy','Admin\SettingController@update_privacy_policy');

    Route::post('update_notification_setting','Admin\SettingController@update_notification_setting');
    Route::post('update_coworker_notification_setting','Admin\SettingController@update_coworker_notification_setting');

    Route::post('update_sms_setting','Admin\SettingController@update_sms_setting');
    Route::post('update_user_verification','Admin\SettingController@update_user_verification');

    Route::post('update_password','Admin\AdminController@update_password');
    Route::post('offer_category','Admin\OfferController@offer_category');

    Route::post('update_license','Admin\AdminController@update_license');
    Route::post('update_offer_category','Admin\OfferController@update_offer_category');
    Route::post('change_color','Admin\AdminController@change_color');

    Route::post('category/change_status','Admin\CategoryController@change_status');
    Route::post('coworkers/change_status','Admin\CoworkersController@change_status');
    Route::post('service/change_status','Admin\ServiceController@change_status');
    Route::post('language/change_status','Admin\LanguageController@change_status');

    Route::get('block/{id}','Admin\HomeController@block');
    Route::get('unblock/{id}','Admin\HomeController@unblock');

    Route::post('appointment_status','Admin\AppointmentController@appointment_status');
    Route::get('/home', 'Admin\HomeController@index')->name('home');
});

Route::post('saveEnvData','AdminController@saveEnvData');

// Employee
Route::get('coworker/coworker_login','Employee\EmployeeController@employee_login');
Route::post('coworker/coworker_confirm_login','Employee\EmployeeController@employee_confirm_login');

Route::get('coworker/coworker_register','Employee\EmployeeController@coworker_register');
Route::post('coworker/coworker_confirm_register','Employee\EmployeeController@coworker_confirm_register');

Route::middleware(['auth'])->prefix('coworker')->group(function ()
{
    Route::get('coworker_home','Employee\EmployeeController@coworker_home');
    Route::get('appointment','Employee\EmployeeController@appointment');
    Route::get('worker_profile','Employee\EmployeeController@worker_profile');
    Route::get('worker_review','Employee\EmployeeController@worker_review');
    Route::post('update_employee','Employee\EmployeeController@update_employee');
    Route::post('employee_change_password','Employee\EmployeeController@apiChangePassword');

    Route::resources([
        'portfolio' => 'Employee\EmployeePortFolioController',
    ]);
});
