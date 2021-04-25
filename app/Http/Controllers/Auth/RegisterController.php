<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Coworkers;
use App\Models\Station;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required|digits:10|numeric',
            'car_wash' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function create(Request $data)
    {

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'image' => 'noimage.jpg',
            'status' => 1,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $role = $data['car_wash'];
        $role_id = Role::where('name',$role)->first();
        $user->roles()->sync($role_id);

        if($role == 'station'){

            Station::create([
                'name' => $data['name'],
                'user_id' => $user->id,
                'image' => 'noimage.jpg',
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => 1,
                'password' => Hash::make($data['password']),
                'location' => "no location",
                'start_time' => '08:00 AM',
                'end_time' => '08:00 PM',
            ]);

        }else if($role == 'coworker'){
            Coworkers::create([
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
        }
        return redirect('/')->with('message', 'Register Success');
    }
}
