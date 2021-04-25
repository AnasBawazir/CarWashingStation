<?php

namespace App\Http\Controllers\Employee;

use App\Models\Appointment;
use App\Models\CoworkerPortfolio;
use App\Models\Coworkers;
use App\Models\Faq;
use App\Http\Controllers\Controller;
use App\Mail\user_verification;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Station;
use App\Models\TimeSlot;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Client;
use Stripe\Order;

class EmployeeController extends Controller
{
    public function employee_login()
    {
        return view('coworker.coworker.coworker_login');
    }


    public function show_register()
    {
        return view('auth.register');
    }

    public function register(Request $data)
    {
        $data->validate([
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required|digits:10|numeric',
            'car_wash' => ['required'],
        ]);
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'image' => 'noimage.jpg',
            'status' => 1,
            'phone' => $data['phone'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);

        $role = $data['car_wash'];
        $role_id = Role::where('name',$role)->first();
        $user->roles()->sync($role_id);

        if($role == 'coworker'){
            $worker = Coworkers::create([
                'name' => $data['name'],
                'user_id' => $user->id,
                'image' => 'noimage.jpg',
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => 1,
                'password' => Hash::make($data['password']),
                'start_time' => '08:00 AM',
                'end_time' => '08:00 PM',
            ]);

        }elseif ($role == 'stations'){
            $station = Station::create([
                'name' => $data['name'],
                'user_id' => $user->id,
                'image' => 'noimage.jpg',
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => 1,
                'location' => "no location",
                'start_time' => '08:00 AM',
                'end_time' => '08:00 PM',
            ]);
        }
        return redirect()->back()->with('message', 'Register Success');

//        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
//        {
//            return redirect('coworker/coworker_home');
//        }
    }

    public function coworker_home()
    {
        $coworker = Coworkers::where('user_id',auth()->user()->id)->first();
        $appointments = Appointment::where('coworker_id',$coworker->id)->count();
        $reviews = Review::where('coworker_id',$coworker->id)->count();
        $today_appointments = Appointment::orderBy('id','DESC')->where([['coworker_id',$coworker->id],['date', date("Y-m-d")]])->get();
        $currency = Setting::first()->currency_symbol;
        return view('coworker.coworker.coworker_home',compact('appointments','currency','reviews','today_appointments'));
    }

    public function appointment()
    {
        $coworker = Coworkers::where('user_id',auth()->user()->id)->first();
        $ongoings = Appointment::orderBy('id','DESC')->where([['coworker_id',$coworker->id],['date', '>=', date("Y-m-d")],['appointment_status', '!=', 'COMPLETE'],['appointment_status', '!=', 'CANCEL']])->get();
        $pasts = Appointment::orderBy('id','DESC')->where([['coworker_id',$coworker->id],['date', '<', date("Y-m-d")]])->orWhere([['date', '>=', date("Y-m-d")],['appointment_status', 'COMPLETE']])->orWhere([['date', '>=', date("Y-m-d")],['appointment_status', 'CANCEL']])->get();
        $currency = Setting::first()->currency_symbol;

        return view('coworker.worker appointment.appointment',compact('ongoings','currency','pasts'));
    }

    public function worker_review()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $reviews = Review::where('coworker_id',$worker->id)->get();
        return view('coworker.coworker.review',compact('reviews'));
    }

    public function worker_profile()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $timeslots = TimeSlot::get();
        return view('coworker.coworker.coworker_profile',compact('worker','timeslots'));
    }

    public function apiUpdateEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'bail|required|email',
            'phone' => 'bail|required|digits:10|numeric',
            'start_time' => 'required',
            'end_time' => 'bail|required|after:start_time',
            'experience' => 'required|numeric',
            'description' => 'required',
        ]);

        $data = $request->all();
        $id = Coworkers::where('user_id',auth()->user()->id)->first();
        if($request->password == null)
        {
            $data['password'] = $id->password;
        }
        else
        {
            $request->validate([
                'password' => 'bail|min:6',
            ]);
            $data['password'] = Hash::make($request->password);
        }
        if ($file = $request->hasfile('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        $id->update($data);
        return redirect('coworker/coworker_home');
    }

    public function apiNotification()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $notifications = Notification::where([['user_type','driver'],['user_id',$worker->id]])->get()->each->setAppends([]);
        foreach ($notifications as $notification)
        {
            $user = Appointment::find($notification->order_id);
            $notification['users'] = User::find($user->user_id,['name','image']);
            $notification['date'] = Carbon::parse($notification->created_at)->format('Y-m-d');
            $notification['time'] = Carbon::parse($notification->created_at)->format('h:i a');
        }
        return response(['success' => true , 'data' => $notifications]);
    }

    public function apiSingleAppointment($id)
    {
        $appointment = Appointment::find($id);
        return response(['success' => true , 'data' => $appointment]);
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

    public function apiEmployeeFaq()
    {
        $faqs = Faq::where('for','driver')->get();
        return response(['success' => true , 'data' => $faqs]);
    }

    public function apiAppointments()
    {
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $appointments = Appointment::where('coworker_id',$worker->id)->Where('appointment_status','!=','PENDING')->get();
        return response(['success' => true , 'data' => $appointments]);
    }

    public function apiChangeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'appointment_status' => 'required'
        ]);
        $id = Appointment::find($request->id);
        $id->appointment_status = strtoupper($request->appointment_status);
        $id->save();
        return response(['success' => true , 'data' => $id]);
    }

    public function apiTimeslots()
    {
        $data = TimeSlot::get(['time']);
        return response(['success' => true , 'data' => $data]);
    }
}
