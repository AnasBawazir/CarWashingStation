<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\CoworkerPortfolio;
use App\Models\Coworkers;
use App\Mail\StatusChange;
use App\Mail\user_verification;
use App\Models\NotificationTemplate;
use App\Models\Review;
use App\Models\Service;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Notifications\Notification;
use OneSignal;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Client;
use App\Mail\ForgotPassword;

class EmployeeApiController extends Controller
{
    public function apiEmployeeLogin(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $coworker = Auth::user()->load('roles');
            if($coworker->status == 1)
            {
                if ($coworker->roles->contains('name', 'employee'))
                {
                    if($coworker->is_verified == 1)
                    {
                        $coworker['token'] =  $coworker->createToken('Shinewash')->accessToken;
                        return response(['success' => true , 'data' => $coworker]);
                    }
                    else
                    {
                        $admin_verify_user = Setting::find(1)->user_verification;
                        if($admin_verify_user == 1)
                        {
                            // $otp = mt_rand(1000, 9999);
                            $otp = 1234;

                            $sms_verification = Setting::first()->sms_verification;
                            $mail_verification = Setting::first()->mail_verification;

                            $verification_content = NotificationTemplate::where('title','user verification')->first();

                            $msg_content = $verification_content->notification_content;
                            $mail_content =$verification_content->mail_content;

                            $sid = Setting::first()->twilio_acc_id;
                            $token = Setting::first()->twilio_auth_token;

                            $detail['otp'] = $otp;
                            $detail['customer_name'] = $coworker->name;
                            $data = ["{otp}", "{customer_name}"];

                            $coworker->otp = $otp;
                            $coworker->save();
                            if($mail_verification == 1)
                            {
                                $message1 = str_replace($data, $detail, $mail_content);
                                try {
                                    Mail::to($coworker)->send(new user_verification($message1));
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                            if($sms_verification == 1)
                            {
                                try
                                {
                                    $p = $coworker->phone_code + $coworker->phone;
                                    $message1 = str_replace($data, $detail, $msg_content);
                                    $client = new Client($sid, $token);
                                    $client->messages->create(
                                        $p,
                                        array(
                                            'from' => Setting::first()->twilio_phone_no,
                                            'body' => $message1
                                        )
                                    );
                                }
                                catch (\Throwable $th) {}
                            }
                            return response(['success' => true ,'data' => $coworker, 'msg' => 'Otp send in your account']);
                        }
                        else
                        {
                            $coworker['token'] =  $coworker->createToken('Shinewash')->accessToken;
                            return response(['success' => true , 'data' => $coworker]);
                        }
                    }
                }
                else
                {
                    return response(['success' => false , 'data' => 'only employee can login']);
                }
            }
            else
            {
                return response(['success' => false , 'data' => 'You disable by admin please contact admin']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'this credential does not match our record']);
        }
    }

    public function apiEmployeeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required|digits:10|numeric',
            'phone_code' => 'required'
        ]);
        $admin_verify_user = Setting::find(1)->user_verification;
        if ($admin_verify_user == 1) {
            $is_verified = 0;
        } else {
            $is_verified = 1;
        }
        $data = $request->all();
        $password = Hash::make($request->password);
        $user = User::create([
            'name' => $request->name,
            'image' => 'noimage.jpg',
            'phone' => $request->phone,
            'phone_code' => $request->phone_code,
            'password' => $password,
            'status' => 1,
            'email' => $request->email,
            'is_verified' => $is_verified,
        ]);
        $role_id = Role::where('name','employee')->orWhere('name','Employee')->first();
        $user->roles()->sync($role_id);

        $worker = Coworkers::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'image' => 'noimage.jpg',
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 1,
            'password' => $password,
            'start_time' => '08:00 AM',
            'end_time' => '08:00 PM',
        ]);
        if (isset($request->device_token)) {
            $user->device_token = $request->device_token;
            $user->save();
        }

        if ($user->is_verified == 1) {
            $user['token'] = $user->createToken('shinewash')->accessToken;
        } else {
            $admin_verify_user = Setting::find(1)->user_verification;
            if ($admin_verify_user == 1) {
                // $otp = mt_rand(1000, 9999);
                $otp = 1234;

                $sms_verification = Setting::first()->sms_verification;
                $mail_verification = Setting::first()->mail_verification;

                $verification_content = NotificationTemplate::where('title', 'user verification')->first();

                $msg_content = $verification_content->notification_content;
                $mail_content = $verification_content->mail_content;

                $sid = Setting::first()->twilio_acc_id;
                $token = Setting::first()->twilio_auth_token;

                $detail['otp'] = $otp;
                $detail['customer_name'] = $user->name;
                $data = ["{otp}", "{customer_name}"];

                $user->otp = $otp;
                $user->save();
                if ($mail_verification == 1) {
                    $message1 = str_replace($data, $detail, $mail_content);
                    try {
                        Mail::to($user)->send(new user_verification($message1));
                    } catch (\Throwable $th) {
                    }
                }
                if ($sms_verification == 1) {
                    try {
                        $p = $user->phone_code + $user->phone;;
                        $code = $user->phone_code;
                        $message1 = str_replace($data, $detail, $msg_content);
                        $client = new Client($sid, $token);
                        $client->messages->create(
                            $p,
                            array(
                                'from' => Setting::first()->twilio_phone_no,
                                'body' => $message1
                            )
                        );
                    } catch (\Throwable $th) {
                    }
                }
            }
        }
        return response()->json(['success' => true , 'data' => $worker , 'msg' => 'account created successfully..!!'], 200);
    }

    public function apiEmployeeCheckOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'bail|required',
            'otp' => 'bail|required|min:4',
        ]);

        $user = User::find($request->user_id);
        if($user)
        {
            if($user->otp == $request->otp)
            {
                $user->is_verified = 1;
                $user->save();
                $user['token'] = $user->createToken('shinewash')->accessToken;
                return response(['success' => true ,'data' => $user ,'msg' => 'SuccessFully verify your account...!!']);
            }
            else
            {
                return response(['success' => false , 'data' => 'Something went wrong otp does not match..!']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'Oops...user not found..!!']);
        }
    }

    public function apiAppointment()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $appointment = array();
        $appointment['ongoing'] = Appointment::where([['coworker_id',$worker->id],['date', '>=', date("Y-m-d")],['appointment_status', '!=', 'COMPLETE']])->get();
        $appointment['past'] = Appointment::where([['coworker_id',$worker->id],['date', '<', date("Y-m-d")]])->orWhere([['date', '>=', date("Y-m-d")],['appointment_status', 'COMPLETE']])->get();
        return response(['success' => true , 'data' => $appointment]);
    }

    public function apiAddPortfolio(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $img = $request->image;
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data1 = base64_decode($img);
        $Iname = uniqid();
        $file = public_path('/images/upload/') . $Iname . ".png";
        $success = file_put_contents($file, $data1);
        $data['coworker_id'] = $worker->id;
        $data['image'] = $Iname . ".png";
        CoworkerPortfolio::create($data);
        return response(['success' => true , 'data' => 'Image added successfully..!!']);
    }

    public function apiShowPortfolio()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $data = CoworkerPortfolio::where('coworker_id',$worker->id)->get(['id','image']);
        return response(['success' => true , 'data' => $data]);
    }

    public function apiDeletePortfolio($id)
    {
        $id = CoworkerPortfolio::find($id);
        \File::delete(public_path('images/upload/'.$id->image));
        $id->delete();
        return response(['success' => true , 'data' => 'Deleted successfully..!!']);
    }

    public function apiShowWorkerReview()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $ids = Review::where('coworker_id',$worker->id)->get();
        foreach ($ids as $value)
        {
            $value['date'] = Carbon::parse($value->created_at)->format('Y-m-d');
        }
        return response(['success' => true , 'data' => $ids]);
    }

    public function apiEmployee()
    {
        $employee = Coworkers::where('user_id',auth()->user()->id)->first();
        $employee['ongoing'] = Appointment::where([['coworker_id',$employee->id],['date', '>=', date("Y-m-d")],['appointment_status', '!=', 'COMPLETE']])->count();
        $employee['complete'] = Appointment::where([['coworker_id',$employee->id],['date', '<', date("Y-m-d")]])->orWhere([['date', '>=', date("Y-m-d")],['appointment_status', 'COMPLETE']])->count();
        return response(['success' => true , 'data' => $employee]);
    }

    public function apiUpdateEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'bail|required|after:start_time',
            'experience' => 'bail|required|numeric',
            'description' => 'bail|required',
        ]);
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $data = $request->all();
        $worker->update($data);
        return response(['success' => true , 'data' => 'Update successfully..!!']);
    }

    public function apiUpdateImage(Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        $id = Coworkers::where('user_id',auth()->user()->id)->first();
        if(isset($request->image))
        {
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $data['image'] = $Iname . ".png";
        }
        $id->update($data);
        return response(['success' => true , 'data' => 'image updated succssfully..!!']);
    }

    public function apiChangePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'bail|required|min:6',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $id = auth()->user();
        if(Hash::check($data['old_password'], $id->password) == true)
        {
            if($data['password'] == $data['password_confirmation'])
            {
                $id->password = Hash::make($data['password']);
                $id->save();
                return response(['success' => true , 'data' => 'Password Update Successfully...!!']);
            }
            else
            {
                return response(['success' => false , 'data' => 'password and confirm password does not match']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'Old password does not match']);
        }
    }

    public function apiResendOtp(Request $request)
    {
        $request->validate([
            'worker_id' => 'required',
        ]);

        $user = User::find($request->worker_id);
        $admin_verify_user = Setting::find(1)->user_verification;
        if ($admin_verify_user == 1)
        {
            $otp = mt_rand(1000, 9999);

            $sms_verification = Setting::first()->sms_verification;
            $mail_verification = Setting::first()->mail_verification;

            $verification_content = NotificationTemplate::where('title', 'user verification')->first();

            $msg_content = $verification_content->notification_content;
            $mail_content = $verification_content->mail_content;

            $sid = Setting::first()->twilio_acc_id;
            $token = Setting::first()->twilio_auth_token;

            $detail['otp'] = $otp;
            $detail['customer_name'] = $user->name;
            $data = ["{otp}", "{customer_name}"];

            $user->otp = $otp;
            $user->save();
            if ($mail_verification == 1) {
                $message1 = str_replace($data, $detail, $mail_content);
                try {
                    Mail::to($user)->send(new user_verification($message1));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            if ($sms_verification == 1) {
                try {
                    $p = $user->phone_code + $user->phone;
                    $message1 = str_replace($data, $detail, $msg_content);
                    $client = new Client($sid, $token);
                    $client->messages->create(
                        $p,
                        array(
                            'from' => Setting::first()->twilio_phone_no,
                            'body' => $message1
                        )
                    );
                } catch (\Throwable $th) {
                }
            }
        }
        return response(['success' => true, 'data' => $user]);
    }

    public function apiForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        // $password = mt_rand(100000, 999999);
        $password = 123456;
        if ($user) {
            $passwordTemplate = NotificationTemplate::where('title', 'forgot password')->first();
            $mail_content = $passwordTemplate->mail_content;
            $detail['password'] = $password;
            $detail['customer_name'] = $user->name;
            $data = ["{password}", "{customer_name}"];
            if ($user) {
                $user->password = Hash::make($password);
                $user->save();
                $message1 = str_replace($data, $detail, $mail_content);
                try
                {
                    Mail::to($user)->send(new ForgotPassword($message1));
                }
                catch (\Throwable $th)
                {

                }
                return response(['success' => true, 'data' => $user, 'msg' => 'your password send into your email']);
            }
        } else {
            return response(['success' => false, 'data' => 'Oops...user not found..!!']);
        }
    }
}
