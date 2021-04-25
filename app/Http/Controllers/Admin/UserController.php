<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Setting;
use App\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();
        return view('admin.user.user',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['required','numeric','digits:10'],
        ]);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate([
                'image' => 'bail|mimes:jpeg,jpg,png',
            ]);
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        else
        {
            $data['image'] = 'noimage.jpg';
        }
        $data['status'] = 1;
        $data['is_verified'] = 1;
        User::create($data);
        return redirect('admin/user')->with('msg','User created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $appointments = Appointment::where('user_id',$id)->get();
        $appointments_approve = Appointment::where([['user_id',$id],['appointment_status','APPROVE']])->get();
        $appointments_pending = Appointment::where([['user_id',$id],['appointment_status','PENDING']])->get();
        $appointments_complete = Appointment::where([['user_id',$id],['appointment_status','COMPLETE']])->get();
        $appointments_cancel = Appointment::where([['user_id',$id],['appointment_status','CANCEL']])->get();
        $appointments_reject = Appointment::where([['user_id',$id],['appointment_status','REJECT']])->get();
        $currency = Setting::first()->currency_symbol;
        return view('admin.user.user_profile',compact('user','appointments','currency','appointments_approve','appointments_pending','appointments_complete','appointments_cancel','appointments_reject'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required','numeric','digits:10'],
        ]);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate([
                'image' => 'bail|mimes:jpeg,jpg,png',
            ]);
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        $id = User::find($id);
        if($request->password == null)
        {
            $data['password'] = $id['password'];
        }
        else
        {
            $request->validate([
                'password' => ['required', 'string', 'min:6']
            ]);
            $data['password'] = Hash::make($request->password);
        }
        $id->update($data);
        return redirect('admin/user')->with('msg','User updated successfully..!!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::all();
        foreach ($appointment as $value)
        {
            $user_id = explode(',',$value['user_id']);
            if (($key = array_search($id, $user_id)) !== false)
            {
                return response(['success' => false , 'data' => 'This user connected with Appointment first delete appointment']);
            }
        }
        $id = User::find($id);
        $id->delete();
        return response(['success' => true]);
    }
}
