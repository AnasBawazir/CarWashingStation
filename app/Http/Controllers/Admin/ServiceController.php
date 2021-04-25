<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Coworkers;
use App\Models\Service;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $services = Service::all();
        $categories = Category::where('status',1)->get();
        $coworkers = Coworkers::where('status',1)->get();
        return view('admin.services.service',compact('coworkers','categories','services'));
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
            'service_name' => 'required',
            'category_id' => 'required',
            'coworker_id' => 'required',
            'price' => 'bail|required|numeric',
            'description' => 'required',
            'duration' => 'bail|required|numeric',
        ]);
        $data = $request->all();
        $data['category_id'] = implode(',',$data['category_id']);
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
            $request->validate([
                'image' => 'bail|mimes:jpeg,jpg,png',
            ]);
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        Service::create($data);
        return redirect('admin/service')->with('msg','Service created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Service::with('coworker')->where('id',$id)->first();
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
        $id = Service::find($id);
        $id['cat_id'] = explode(',',$id->category_id);
        return response(['success' => true , 'data' => $id]);
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
            'service_name' => 'required',
            'category_id' => 'required',
            'coworker_id' => 'required',
            'price' => 'bail|required|numeric',
            'description' => 'required',
            'duration' => 'bail|required|numeric',
            // 'image' => 'required',
        ]);
        $data = $request->all();
        $id = Service::find($id);
        $data['category_id'] = implode(',',$data['category_id']);
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
        return redirect('admin/service')->with('msg','Service updated successfully..!!');
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
            $services = explode(',',$value->service_id);
            if (($key = array_search($id, $services)) !== false)
            {
                return response(['success' => false , 'data' => 'This service connected with Appointment first delete appointment']);
            }
        }

        $id = Service::find($id);
        if($id->image != 'noimage.jpg')
        {
            \File::delete(public_path('images/upload/'.$id->image));
        }
        $id->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Service::find($request->id);
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
