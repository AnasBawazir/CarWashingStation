<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Coworkers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use LicenseBoxAPI;

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
//    protected $redirectTo = RouteServiceProvider::ADMIN;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:coworker')->except('logout');
    }

    protected function Login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->is_verified == 1) {

                if (Auth::user()->status == 1) {

                    $user = Auth::user()->load('roles');
                    if ($user->roles->contains('name', 'admin')) {
                        return redirect('admin/home');

                    } else if ($user->roles->contains('name', 'coworker')) {
//                        $coworker = Coworkers::where('user_id', auth()->user()->id)->first();
                        return redirect('coworker/coworker_home');
                    }
                } else {
                    return redirect()->back()->withErrors('You disable by admin please contact admin')->withInput();
                }
            } else
                return redirect()->back()->withErrors('Your account not verified')->withInput();

        } else {
            return redirect()->back();
        }

    }
}
