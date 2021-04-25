<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coworkers;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TimeSlot;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class CoworkersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('coworker_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $coworkers = Coworkers::all();
        $timeslots = TimeSlot::all();
        return view('admin.coworkers.coworkers',compact('timeslots','coworkers'));
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
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'image' => 'bail|mimes:jpeg,jpg,png',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required|digits:10|numeric',
            'start_time' => 'required',
            'end_time' => 'bail|required|after:start_time',
            'experience' => 'required|numeric',
            'description' => 'required',
        ]);
        $data = $request->all();
        $password = Hash::make($request->password);
        $user = User::create([
            'name' => $request->name,
            'image' => 'noimage.jpg',
            'phone' => $request->phone,
            'password' => $password,
            'status' => 1,
            'email' => $request->email,
        ]);
        $role_id = Role::where('name','employee')->orWhere('name','Employee')->first();
        $user->roles()->sync($role_id);

        $data['password'] = $password;
        if(isset($data['status']))
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 1;
        }
        if ($file = $request->hasfile('image'))
        {
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
        $data['user_id'] = $user->id;
        $worker = Coworkers::create($data);
        return redirect('admin/coworkers')->with('msg','coworker created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Coworkers::find($id);
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Coworkers::find($id);
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
            'name' => 'required',
            'email' => 'bail|required|email',
            'phone' => 'bail|required|digits:10|numeric',
            'start_time' => 'required',
            'end_time' => 'bail|required|after:start_time',
            'experience' => 'required|numeric',
            'description' => 'required',
        ]);

        $data = $request->all();
        $id = Coworkers::find($id);
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
        if(isset($data['status']))
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 1;
        }
        if ($file = $request->hasfile('image')) {
            $request->validate([
                'image' => 'bail|mimes:jpeg,jpg,png',
            ]);
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        $id->update($data);
        return redirect('admin/coworkers')->with('msg','coworker Updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $services = Service::all();
        foreach ($services as $service)
        {
            $coworker_id = explode(',',$service->coworker_id);
            if(count($coworker_id) > 1)
            {
                if (($key = array_search($id, $coworker_id)) !== false)
                {
                    unset($coworker_id[$key]);
                    $service->coworker_id = implode(',',$coworker_id);
                }
                $service->save();
            }
            else
            {
                if (($key = array_search($id, $coworker_id)) !== false)
                {
                    $serviceId = Service::where('coworker_id',$coworker_id[$key])->first();
                    $serviceId->delete();
                }
            }
        }

        $id = Coworkers::find($id);
        if($id->image != 'noimage.jpg')
        {
            \File::delete(public_path('images/upload/'.$id->image));
        }
        $id->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Coworkers::find($request->id);
        if($data->status == 0)
        {
            $data->status = 1;
            $data->save();
            return response(['success' => true]);
        }
        if($data->status == 1)
        {
            $data->status = 0;
            $data->save();
            return response(['success' => true]);
        }
    }
}
