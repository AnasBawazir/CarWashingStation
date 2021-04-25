<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Coworkers;
use App\Models\Faq;
use App\Mail\ForgotPassword;
use App\Mail\StatusChange;
use App\Models\NotificationTemplate;
use App\Models\Offer;
use App\Models\Service;
use App\Models\CoworkerPortfolio;
use App\Models\Setting;
use App\Models\TimeSlot;
use App\User;
use Hash;
use Auth;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\user_verification;
use App\Models\PaymentSetting;
use App\Models\Review;
use Carbon\Carbon;
use App\Models\Notification;
use Stripe\Stripe;
use OneSignal;
use Config;
use Stripe\Charge;
use Illuminate\Support\Arr;

class UserApiController extends Controller
{
    public function apiLogin(Request $request)
    {
        $request->validate(
            [
                'email' => 'bail|required_if:provider,GOOGLE,LOCAL|email',
                'password' => 'bail|required_if:provider,LOCAL|min:6',
                'name' => 'bail|required_if:provider,GOOGLE,FACEBOOK',
                'image' => 'bail|required_if:provider,GOOGLE,FACEBOOK',
                'provider_token' => 'bail|required_if:provider:GOOGLE,FACEBOOK',
                'provider' => 'bail|required',
            ]
        );
        if ($request->provider == 'LOCAL') {
            $user = ([
                'email' => $request->email,
                'password' => $request->password,
                'status' => 1,
            ]);

            if (Auth::attempt($user)) {
                $user = Auth::user();
                if (isset($request->device_token)) {
                    $user->device_token = $request->device_token;
                    $user->save();
                }
                if ($user['is_verified'] == 1) {
                    $user['token'] =  $user->createToken('shinewash')->accessToken;
                    return response()->json(['success' => true, 'data' => $user], 200);
                } else {
                    $admin_verify_user = Setting::find(1)->user_verification;
                    if ($admin_verify_user == 1) {
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
                            try
                            {
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
                        return response(['success' => true, 'data' => $user, 'msg' => 'Otp send in your account']);
                    }
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Email and password wrong..!!'], 401);
            }
        } else {
            $data = $request->all();
            $data['role'] = 0;
            $data['is_verified'] = 1;
            $filtered = Arr::except($data, ['provider_token']);

            if ($data['provider'] !== 'LOCAL') {
                $email = User::where('email', $data['email'])->first();
                if ($email) {
                    $email->provider_token = $request->provider_token;
                    $token = $email->createToken('Shinewash')->accessToken;
                    $email->save();
                    $email['token'] = $token;
                    return response()->json(['msg' => 'login successfully', 'data' => $email, 'success' => true], 200);
                } else {
                    $data = User::firstOrCreate(['provider_token' => $request->provider_token], $filtered);
                    if ($request->image != null) {
                        $url = $request->image;
                        $contents = file_get_contents($url);
                        $name = substr($url, strrpos($url, '/') + 1);
                        $destinationPath = public_path('/images/upload/') . $name . '.png';
                        file_put_contents($destinationPath, $contents);
                        $data['image'] = $name . '.png';
                    }
                    if (isset($data['device_token'])) {
                        $data['device_token'] = $data->device_token;
                    }
                    $data->save();
                    $token = $data->createToken('Shinewash')->accessToken;
                    $data['token'] = $token;

                    return response()->json(['msg' => 'login successfully', 'data' => $data, 'success' => true], 200);
                }
            }
        }
    }

    public function apiRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['required', 'numeric', 'digits:10'],
        ]);

        $admin_verify_user = Setting::find(1)->user_verification;
        if ($admin_verify_user == 1) {
            $is_verified = 0;
        } else {
            $is_verified = 1;
        }
        $data = $request->all();
        $data['status'] = 1;

        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($data['password']),
            'status' => 1,
            'phone' => $request->phone,
            'phone_code' => $request->phone_code,
            'is_verified' => $is_verified,
            'image' => 'noimage.jpg',
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
        return response()->json(['success' => true, 'data' => $user, 'message' => 'user created successfully!']);
    }

    public function apiSendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);
        $admin_verify_user = Setting::find(1)->user_verification;
        if ($admin_verify_user == 1) {
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

    public function apiCheckOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'bail|required',
            'otp' => 'bail|required|min:4',
        ]);

        $user = User::find($request->user_id);

        if ($user) {
            if ($user->otp == $request->otp) {
                $user->is_verified = 1;
                $user->save();
                $user['token'] = $user->createToken('shinewash')->accessToken;
                return response(['success' => true, 'data' => $user, 'msg' => 'SuccessFully verify your account...!!']);
            } else {
                return response(['success' => false, 'data' => 'Something went wrong otp does not match..!']);
            }
        } else {
            return response(['success' => false, 'data' => 'Oops...user not found..!!']);
        }
    }

    public function apiForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        $password = mt_rand(100000, 999999);
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
                // try
                // {
                Mail::to($user)->send(new ForgotPassword($message1));
                // }
                // catch (\Throwable $th)
                // {

                // }
                return response(['success' => true, 'data' => $user, 'msg' => 'your password send into your email']);
            }
        } else {
            return response(['success' => false, 'data' => 'Oops...user not found..!!']);
        }
    }

    public function apiSerchCategory(Request $request)
    {
        $q = $request->category_name;
        $reqData = Category::where('category_name', 'LIKE', '%' . $q . "%")->get();
        if (count($reqData) > 0) {
            return response(['success' => true, 'data' => $reqData]);
        } else return response(['success' => false, 'msg' => 'No data found try to search again..!!']);
    }

    public function apiNotification()
    {
        $data = Notification::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $data]);
    }

    public function apiAllAppointment()
    {
        $data = Appointment::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get()->each->setAppends(['service', 'user', 'coworker']);;
        return response(['success' => true, 'data' => $data]);
    }

    public function apiShowAppointment($id)
    {
        $data = Appointment::where([['id', $id], ['user_id', auth()->user()->id]])->first();
        return response(['success' => true, 'data' => $data]);
    }

    public function apistripe(Request $request)
    {
        $request->validate([
            'source' => 'bail|required',
            'amount' => 'bail|required',
        ]);

        $paymentSetting = PaymentSetting::find(1);
        $stripe_sk = $paymentSetting->stripe_secret_key;

        $currency = setting::find(1)->currency;

        // $stripe = Stripe::make($stripe_sk);
        $charge = $stripe->charges()->create([
            'source' => $request->source,
            'currency' => $currency,
            'amount'   => $request->amount,
        ]);

        // return $charge['id'];
    }

    public function apiService()
    {
        $service = Service::with('Coworker')->where('status', 1)->orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $service]);
    }

    public function apiCategory()
    {
        $category = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $category]);
    }

    public function apiCoworker()
    {
        $coworker = Coworkers::where('status', 1)->orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $coworker]);
    }

    public function apiOffer()
    {
        $offer = Offer::orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $offer]);
    }

    public function apiSingel_coworker($id)
    {
        $single_worker = Coworkers::where([['id', $id], ['status', 1]])->first();
        $single_worker['skills'] = Service::where([['coworker_id', $id], ['status', 1]])->orderBy('id', 'DESC')->get();
        $single_worker['images'] = CoworkerPortfolio::where('coworker_id',$id)->get(['image']);
        return response(['success' => true, 'data' => $single_worker]);
    }

    public function apiTime_slots(Request $request)
    {
        $worker_time = Coworkers::where([['id', $request->id], ['status', 1]])->first();
        $master = [];

        $start_time = new Carbon($request['date'] . ' ' . $worker_time->start_time);
        if ($request->date == Carbon::now()->format('Y-m-d')) {
            $t = Carbon::now('Asia/Kolkata');
            $minutes = date('i', strtotime($t));
            if ($minutes <= 30) {
                $add = 30 - $minutes;
            } else {
                $add = 60 - $minutes;
            }
            $add += 60;
            $d = $t->addMinutes($add)->format('h:i a');
            $start_time = new Carbon($request['date'] . ' ' . $d);
        }

        $end_time = new Carbon($request['date'] . ' ' . $worker_time->end_time);

        $diff_in_minutes = $start_time->diffInMinutes($end_time);
        for ($i = 0; $i <= $diff_in_minutes; $i += 30) {
            if ($start_time >= $end_time) {
                break;
            } else {
                $temp['start_time'] = $start_time->format('h:i a');
                $temp['end_time'] = $start_time->addMinutes('30')->format('h:i a');
                $time = strval($temp['start_time']);
                $appointment = Appointment::where([['coworker_id', $request->id], ['start_time', $time], ['date', $request->date]])->first();
                if ($appointment) {
                    $st = new Carbon($request['date'] . ' ' . $appointment->start_time);
                    $et = $st->addMinutes($appointment->duration)->format('h:i a');
                    if ($temp['start_time'] < $st && $temp['start_time'] > $et || $temp['end_time'] < $et) {
                        $start_time = new Carbon($request['date'] . ' ' . $et);
                        $minutes = date('i', strtotime($start_time));
                        if ($minutes <= 30) {
                            $add = 30 - $minutes;
                        } else {
                            $add = 60 - $minutes;
                        }
                        $d = $start_time->addMinutes($add)->format('h:i a');
                        $start_time = new Carbon($request['date'] . ' ' . $d);
                    }
                } else {
                    array_push($master, $temp);
                }
            }
        }
        return response(['success' => true, 'data' => $master]);
    }

    public function apiEditProfile()
    {
        $user = auth()->user();
        return response(['success' => true, 'data' => $user]);
    }

    public function apiUpdateUser(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'bail|required',
        ]);
        $id = auth()->user();
        $id->update($data);
        return response(['success' => true, 'data' => 'Update Successfully']);
    }

    public function apiUpdateImage(Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        $id = auth()->user();
        if (isset($request->image)) {
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
        return response(['success' => true, 'data' => 'image updated succssfully..!!']);
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
        if (Hash::check($data['old_password'], $id->password) == true) {
            if ($data['password'] == $data['password_confirmation']) {
                $id->password = Hash::make($data['password']);
                $id->save();
                return response(['success' => true, 'data' => 'Password Update Successfully...!!']);
            } else {
                return response(['success' => false, 'data' => 'password and confirm password does not match']);
            }
        } else {
            return response(['success' => false, 'data' => 'Old password does not match']);
        }
    }

    public function apicategory_wise_service($id)
    {
        $category = Category::find($id);
        $services = Service::where('status', 1)->get();
        $data = array();
        foreach ($services as $service) {
            if (in_array($category->id, explode(',', $service->category_id)) > 0) {
                array_push($data, $service);
            }
        }
        return response(['success' => true, 'data' => $data]);
    }

    public function apicategory_wise_service_coworker(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'coworker_id' => 'required',
        ]);
        $data = $request->all();
        $category = Category::find($data['category_id']);
        $coworker = Coworkers::find($data['coworker_id']);
        $services = Service::where('status', 1)->get();
        $data = array();
        foreach ($services as $service) {
            if (in_array($coworker->id, explode(',', $service->coworker_id)) > 0 && in_array($category->id, explode(',', $service->category_id)) > 0) {
                array_push($data, $service);
            }
        }
        return response(['success' => true, 'data' => $data]);
    }

    public function apiFaq()
    {
        $data = Faq::orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $data]);
    }

    public function apiPrivacyPolicy()
    {
        $data = Setting::first()->privacy_policy;
        return response(['success' => true, 'data' => $data]);
    }

    public function apiPaymentSetting()
    {
        $data = PaymentSetting::first();
        return response(['success' => true, 'data' => $data]);
    }

    public function apiSetting()
    {
        $data = Setting::first();
        return response(['success' => true, 'data' => $data]);
    }

    public function apiBookAppoinment(Request $request)
    {
        $request->validate([
            'coworker_id' => 'required',
            'service_id' => 'required',
            'amount' => 'required|numeric',
            'payment_type' => 'required',
            'service_type' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:h:i a',
            'payment_status' => 'required',
            'address' => 'required_if:service_type,HOME',
            'lat' => 'required_if:service_type,HOME',
            'lang' => 'required_if:service_type,HOME',
            'payment_token' => 'required_if:payment_type,Stripe,Paypal,Razor'
        ]);
        $appoint = new Appointment();
        $appoint->appointment_id = '#' . rand(100000, 999999);
        $data = $request->all();

        $duration = Service::whereIn('id',$data['service_id'])->sum('duration');

        $appoint->service_id = implode(',', $data['service_id']);
        $appoint->coworker_id = $data['coworker_id'];

        $start_time = Carbon::parse($data['start_time']);
        $appoint->start_time = $data['start_time'];

        $appoint->end_time = $start_time->addMinutes($duration)->format('h:i a');
        $appoint->duration = $duration;
        $appoint->amount = $data['amount'];

        $appoint->payment_type = $data['payment_type'];
        $appoint->service_type = $data['service_type'];

        $appoint->payment_status = $data['payment_status'];
        $appoint->appointment_status = 'PENDING';

        $appoint->user_id = auth()->user()->id;
        $appoint->date = $data['date'];
        $appoint->appointment_status = 'PENDING';

        if (isset($data['address']))
        {
            $appoint->address = $data['address'];
            $appoint->lat = $data['lat'];
            $appoint->lang = $data['lang'];
        }

        if(isset($data['coupen_id']))
        {
            $appoint->lang = $data['coupen_id'];
            $appoint->discount = $data['discount'];
        }

        if ($request->payment_type == 'Stripe') {
            $paymentSetting = PaymentSetting::find(1);
            $stripe_sk = $paymentSetting->stripe_secret_key;
            $currency = setting::find(1)->currency;
            $stripe = new \Stripe\StripeClient($stripe_sk);
            $stripeDetail = $stripe->charges->create([
                'amount' => intval($request->amount) * 100,
                'currency' => $currency,
                'source' => $request->payment_token,
            ]);
            $appoint->payment_token = $stripeDetail['id'];
        }
        $appoint->save();
        $appointment = Appointment::find($appoint->id);
        $serviceName = [];
        $serivces = Service::whereIn('id', explode(',', $appointment->service_id))->get();
        foreach ($serivces as $value) {
            array_push($serviceName, $value->service_name);
        }

        $approve_content = NotificationTemplate::where('title', 'user appointment book')->first();
        $notification_content = $approve_content->notification_content;
        $user = auth()->user();
        $detail['customer_name'] = $user->name;
        $detail['appointment_id'] = $appointment->appointment_id;
        $detail['company_name'] = Setting::find(1)->company_name;
        $data = ["{customer_name}", "{appointment_id}", "{company_name}"];

        $message1 = str_replace($data, $detail, $notification_content);
        if (Setting::find(1)->push_notification == 1)
        {
            try {
                Config::set('onesignal.app_id', env('APP_ID'));
                Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
                OneSignal::sendNotificationToUser(
                    $message1,
                    $user->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    Setting::find(1)->company_name
                );
            }
            catch (\Throwable $th)
            {
                //throw $th;
            }
        }
        $item = array();
        $item['user_id'] = auth()->user()->id;
        $item['order_id'] = $appointment->id;;
        $item['title'] = 'appointment booked';
        $item['message'] = $message1;
        $item['user_type'] = 'user';
        Notification::create($item);


        $approve_content = NotificationTemplate::where('title', 'worker appointment book')->first();
        $Wnotification_content = $approve_content->notification_content;
        $Wdetail['worker_name'] = $appointment->coworker['name'];
        $Wdetail['appointment_id'] = $appointment->appointment_id;
        $Wdetail['date'] = $appointment->date;
        $Wdetail['start_time'] = $appointment->start_time;
        $Wdata = ["{worker_name}", "{appointment_id}", "{date}","{start_time}"];
        $Wmessage1 = str_replace($Wdata, $Wdetail, $Wnotification_content);
        try
        {
            Config::set('onesignal.app_id', env('COWORKER_APP_ID'));
            Config::set('onesignal.rest_api_key', env('COWORKER_REST_API_KEY'));
            Config::set('onesignal.user_auth_key', env('COWORKER_AUTH_KEY'));
            OneSignal::sendNotificationToUser(
                $Wmessage1,
                $user->device_token,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                Setting::find(1)->company_name
            );
        }
        catch (\Throwable $th)
        {
            //throw $th;
        }
        $item = array();
        $item['user_id'] = auth()->user()->id;
        $item['order_id'] = $appointment->id;;
        $item['title'] = 'appointment booked';
        $item['message'] = $message1;
        $item['user_type'] = 'driver';

        Notification::create($item);
        return response(['success' => true, 'data' => 'appointment booked wait for confirmation']);
    }

    public function apiAddReview(Request $request)
    {
        $request->validate([
            'rate' => 'required',
            'comment' => 'required',
        ]);
        $data = $request->all();
        if (Review::where([['order_id', $data['order_id'], ['user_id', auth()->user()->id]]])->exists() != true) {
            $data['user_id'] = auth()->user()->id;
            $data['coworker_id'] = Appointment::find($data['order_id'])->coworker_id;
            $data['service_id'] = Appointment::find($data['order_id'])->service_id;
            Review::create($data);
            return response(['success' => true, 'data' => 'Thank you for this review']);
        } else {
            return response(['success' => false, 'data' => 'Review already addedd...!!']);
        }
    }

    public function apiCancelAppoinment(Request $request)
    {
        $data = $request->all();
        $order = Appointment::find($data['order_id']);
        $order->appointment_status = 'CANCEL';
        $order->save();

        if ($order['payment_type'] == 'Stripe') {
            $paymentSetting = PaymentSetting::first();
            $stripe_sk = $paymentSetting->stripe_secret_key;
            $currency = setting::find(1)->currency;
            $stripe = new \Stripe\StripeClient(
                $stripe_sk
            );
            $stripe->refunds->create([
                'charge' => $order['payment_token']
            ]);
        }

        $serviceNames = [];
        $serivces = Service::whereIn('id', explode(',', $order->service_id))->get();
        foreach ($serivces as $value) {
            array_push($serviceNames, $value->service_name);
        }
        $approve_content = NotificationTemplate::where('title', 'appointment cancel')->first();
        $notification_content = $approve_content->notification_content;

        $approve_content = NotificationTemplate::where('title', 'appointment cancel')->first();
        $mail_content = $approve_content->mail_content;

        $detail_mail['Customer_name'] = auth()->user()->name;
        $detail_mail['Service_name'] = implode(',', $serviceNames);

        $detail_mail['Company_name'] = setting::find(1)->company_name;
        $detail_mail['Company_website'] = setting::find(1)->website;
        $data_mail = ["{customer_name}", "{service_name}", "{company_name}", "{company_website}"];

        if (Setting::find(1)->mail_notification == 1) {
            $message1 = str_replace($data_mail, $detail_mail, $mail_content);
            try {
                Mail::to(auth()->user())->send(new StatusChange($message1));
            } catch (\Throwable $th) {
            }
        }

        $detail['customer_name'] = auth()->user()->name;
        $data = ["{customer_name}"];
        $message1 = str_replace($data, $detail, $notification_content);
        if (Setting::find(1)->push_notification == 1) {
            if (auth()->user()->device_token != null) {
                try {
                    OneSignal::sendNotificationToUser(
                        $message1,
                        auth()->user()->device_token,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null,
                        setting::find(1)->company_name
                    );
                } catch (\Throwable $th) {
                }
            }
        }
        $item = [];
        $item['user_id'] = auth()->user()->id;
        $item['order_id'] = $order->id;
        $item['user_type'] = 'user';
        $item['title'] = 'appointment cancel';
        $item['message'] = $message1;
        Notification::create($item);
        return response(['success' => true, 'data' => 'cancel appointment.!']);
    }
}
