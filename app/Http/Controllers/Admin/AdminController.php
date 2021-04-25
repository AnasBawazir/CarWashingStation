<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Coworkers;
use App\Models\Service;
use App\Models\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use LicenseBoxAPI;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use OneSignal;
use App;
use App\Models\Language;
use App\Models\NotificationTemplate;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function edit()
    {
        $admin = User::where('id',1)->first();
        return view('admin.admin.admin',compact('admin'));
    }
public function show()
    {
        $admin = User::where('role',1)->first();
        return view('admin.admin.admin',compact('admin'));
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ]);
        $data = $request->all();
        $id = User::find($request->id);

        if(Hash::check($data['old_password'], $id->password) == true)
        {
            $id->password = Hash::make($data['password']);
            $id->save();
            return redirect('admin/home')->with('message','Password Update Successfully...!!');
        }
        else
        {
            return redirect('admin/edit_profile')->with('message','Old password does not match');
        }
    }



    public function notification()
    {
        $users = User::where('id', '!=' ,auth()->user()->id)->where('status',1)->get();
        return view('admin.notification.notification',compact('users'));
    }

    public function send_notification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'msg' => 'required',
            'user_id' => 'required',
        ]);

        $str = json_encode($request->user_id);
        $ids =  str_replace('"', '', $str);
        foreach (json_decode($ids) as $key)
        {
            $user = User::find($key);
            if($user->device_token != null)
            {
                OneSignal::sendNotificationToUser(
                    $request->msg,
                    $user->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    $request->title
                );
            }
        }
        return redirect('admin/notification')->with('msg','Notification sent to all users');
    }

    public function update_admin_profile(Request $request)
    {
        $data = $request->all();
        $id = User::find($request->id);
        if ($file = $request->hasfile('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        $id->update($data);
        return redirect('admin/home');
    }

    public function calendar()
    {
        abort_if(Gate::denies('admin_appointment_calender'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $booking = Appointment::all();
        $event = [];
        foreach ($booking as $row)
        {
            $start_time = strtolower(Carbon::parse($row->start_time)->format('H:i'));
            $end_time = strtolower(Carbon::parse($row->end_time)->format('H:i'));

            $_start = date('Y-m-d H:i:s', strtotime("$row->date $start_time"));
            $_end = date('Y-m-d H:i:s', strtotime("$row->date $end_time"));
            if ($row->appointment_status == "CANCEL")
            {
                $bgColor = "rgba(251, 175, 190, .5)";
                $textColor = "#b3092b";
            }
            else if ($row->appointment_status == "PENDING")
            {
                $bgColor = "rgba(203, 210, 246, .5)";
                $textColor = "#2236a8";
            }
            else if ($row->appointment_status == "APPROVE")
            {
                $bgColor = "rgba(136, 230, 247, .5)";
                $textColor = "#05879e";
            }
            else if ($row->appointment_status == "COMPLETE")
            {
                $bgColor = "rgba(147, 231, 195, .5)";
                $textColor = "#1a8a59";
            }
            //Reject
            else
            {
                $bgColor = "rgb(110, 241, 29)";
                $textColor = "#111111";
            }

            $event[] = Calendar::event(
                    $row->user->name,
                    false,
                    $_start,
                    $_end,
                    $row->id,
                    [
                        'color' => $bgColor,
                        'textColor' => $textColor,
                    ]
                );
        }

        $calendar = Calendar::addEvents($event)
                ->setCallbacks(['eventClick' => 'eventClicked']);
        return view('admin.calendar.calender',compact('calendar'));
    }

    public function change_language($name)
    {
        App::setLocale($name);
        session()->put('locale', $name);
        $direction = Language::where('name',$name)->first()->direction;
        session()->put('direction', $direction);
        return redirect()->back();
    }
    public function calendarData($id)
    {
        $data = Appointment::find($id);
        return response(['success' => true , 'data' => $data]);
    }



    public function appointment_service(Request $request)
    {
        $reqData = $request->all();
        $category = Category::find($reqData['category_id']);
        if($category)
        {
            $coworker = Coworkers::find($reqData['coworker_id']);
            if($coworker)
            {
                $services = Service::where('status',1)->get();
                $data = array();
                foreach ($services as $service)
                {
                    if(in_array($coworker->id, explode(',',$service->coworker_id))>0 && in_array($category->id, explode(',',$service->category_id))>0)
                    {
                        array_push($data,$service);
                    }
                }
                return response(['success' => true , 'data' => $data]);
            }
            else
            {
                return response(['success' => false ]);
            }
        }
        else
        {
            return response(['success' => false ]);
        }
    }

    public function saveEnvData(Request $request)
    {
        $data['DB_HOST'] = $request->db_host;
        $data['DB_DATABASE'] = $request->db_name;
        $data['DB_USERNAME'] = $request->db_user;
        $data['DB_PASSWORD'] = $request->db_pass;

        $envFile = app()->environmentFilePath();

        if ($envFile)
        {
            $str = file_get_contents($envFile);
            if (count($data) > 0) {
                foreach ($data as $envKey => $envValue) {
                    $str .= "\n"; // In case the searched variable is in the last line without \n
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    // If key does not exist, add it
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
                return response()->json(['data' => null, 'success' => false], 200);
            }
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Setting::find(1)->update(['license_code' => $request->license_code , 'client_name' => $request->client_name , 'license_verify' => 1]);
            return response()->json(['data' => url('/login'), 'success' => true], 200);
        }
    }
}
