<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function ()
{
    // User
    Route::get('edit_profile','UserApiController@apiEditProfile');
    Route::post('update_profile','UserApiController@apiUpdateUser');
    Route::post('change_password','UserApiController@apiChangePassword');
    Route::post('book_appoinment','UserApiController@apiBookAppoinment');
    Route::post('cancel_appoinment','UserApiController@apiCancelAppoinment');
    Route::post('stripe','UserApiController@apistripe');
    Route::post('refund_stripe','UserApiController@apiRefundStripe');
    Route::post('cancel_appointment','UserApiController@apiCancelAppoinment');
    Route::get('notification','UserApiController@apiNotification');
    Route::get('appointment','UserApiController@apiAllAppointment');
    Route::get('show_appointment/{id}','UserApiController@apiShowAppointment');
    Route::post('update_image','UserApiController@apiUpdateImage');
    Route::post('add_review','UserApiController@apiAddReview');
});

Route::middleware('auth:api')->group(function ()
{
    // Employee
    Route::get('employee','EmployeeApiController@apiEmployee');
    Route::get('coworker_appointment','EmployeeApiController@apiAppointment');
    Route::post('add_portfolio','EmployeeApiController@apiAddPortfolio');
    Route::get('show_portfolio','EmployeeApiController@apiShowPortfolio');
    Route::get('delete_portfolio/{id}','EmployeeApiController@apiDeletePortfolio');
    Route::get('coworker_review','EmployeeApiController@apiShowWorkerReview');
    Route::post('update_employee','EmployeeApiController@apiUpdateEmployee');
    Route::post('update_employee_image','EmployeeApiController@apiUpdateImage');
    Route::post('employee_change_password','EmployeeApiController@apiChangePassword');
    Route::get('employee_notification','Employee\EmployeeController@apiNotification');
    Route::get('appointments','Employee\EmployeeController@apiAppointments');
    Route::get('single_appointment/{appointment_id}','Employee\EmployeeController@apiSingleAppointment');

    Route::post('change_status','Employee\EmployeeController@apiChangeStatus');
});

Route::get('timeslots','Employee\EmployeeController@apiTimeslots');
Route::get('employee_faq','Employee\EmployeeController@apiEmployeeFaq');

Route::post('send_otp','UserApiController@apiSendOtp');
Route::post('check_otp','UserApiController@apiCheckOtp');

Route::post('forgot_password','UserApiController@apiForgotPassword');
Route::post('login','UserApiController@apiLogin');

Route::post('register','UserApiController@apiRegister');
Route::get('setting','UserApiController@apiSetting');
Route::get('payment_setting','UserApiController@apiPaymentSetting');

Route::post('search_category','UserApiController@apiSerchCategory');

Route::get('service','UserApiController@apiService');
Route::get('category','UserApiController@apiCategory');

Route::get('all_coworker','UserApiController@apiCoworker');
Route::get('offer','UserApiController@apiOffer');

Route::get('faq','UserApiController@apiFaq');
Route::get('privacy_policy','UserApiController@apiPrivacyPolicy');

Route::get('category_wise_service/{id}','UserApiController@apicategory_wise_service');
Route::post('category_wise_service_coworker','UserApiController@apicategory_wise_service_coworker');

Route::get('single_coworker/{id}','UserApiController@apiSingel_coworker');
Route::post('time_slots','UserApiController@apiTime_slots');


//Employee
Route::post('employee_login','EmployeeApiController@apiEmployeeLogin');
Route::post('employee_register','EmployeeApiController@apiEmployeeRegister');
Route::post('employee_check_otp','EmployeeApiController@apiEmployeeCheckOtp');
Route::post('employee_resend_otp','EmployeeApiController@apiResendOtp');
Route::post('employee_forgot_password','EmployeeApiController@apiForgotPassword');
