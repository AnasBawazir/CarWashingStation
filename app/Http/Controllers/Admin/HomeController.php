<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Coworkers;
use App\Models\Service;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        abort_if(Gate::denies('admin_dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $coworkers = Coworkers::get()->take(5);
        $services = Service::get()->take(5);
        return view('home',compact('coworkers','services'));
    }

    // public function appointment()
    // {
    //     $appoinments = Appointment::all();
    //     return view('appointment.appointment',compact('appoinments'));
    // }

    public function block($id)
    {
        User::find($id)->update(['status' => 0]);
        return redirect('admin/user');
    }

    public function unblock($id)
    {
        User::find($id)->update(['status' => 1]);
        return redirect('admin/user');
    }

    public function AppointmentChart()
    {
        $masterYear = array();
        $labelsYear = array();

        array_push($masterYear, Appointment::whereMonth('created_at', Carbon::now())
        ->count());
        for ($i = 1; $i <= 11; $i++) {
            if ($i >= Carbon::now()->month) {
                array_push($masterYear, Appointment::whereMonth('created_at',
                Carbon::now()->subMonths($i))
                ->whereYear('created_at', Carbon::now()->subYears(1))
                ->count());
            } else {
                array_push($masterYear, Appointment::whereMonth('created_at', Carbon::now()->subMonths($i))
                ->whereYear('created_at', Carbon::now()->year)
                ->count());
            }
        }

        array_push($labelsYear, Carbon::now()->format('M-y'));
        for ($i = 1; $i <= 11; $i++) {
            array_push($labelsYear, Carbon::now()->subMonths($i)->format('M-y'));
        }
        return ['data' => $masterYear, 'label' => $labelsYear];
    }

    public function userChart()
    {
        $masterYear = array();
        $labelsYear = array();

        array_push($masterYear, User::whereMonth('created_at', Carbon::now())
        ->count());
        for ($i = 1; $i <= 11; $i++) {
            if ($i >= Carbon::now()->month) {
                array_push($masterYear, User::whereMonth('created_at',
                Carbon::now()->subMonths($i))
                ->whereYear('created_at', Carbon::now()->subYears(1))
                ->count());
            } else {
                array_push($masterYear, User::whereMonth('created_at', Carbon::now()->subMonths($i))
                ->whereYear('created_at', Carbon::now()->year)
                ->count());
            }
        }

        array_push($labelsYear, Carbon::now()->format('M-y'));
        for ($i = 1; $i <= 11; $i++) {
            array_push($labelsYear, Carbon::now()->subMonths($i)->format('M-y'));
        }
        return ['data' => $masterYear, 'label' => $labelsYear];
    }
}
