<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $permission1 = Permission::create(['name' => 'setting_access']);
        $categories = Category::all();
        return view('admin.category.category',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'category_name' => 'required',
        ]);
        $data = $request->all();

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
        Category::create($data);
        return redirect('admin/category')->with('msg','Category created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Category::find($id);
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
            'category_name' => 'required',
        ]);
        $data = $request->all();
        $id = Category::find($id);
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
        $id->update($data);
        return redirect('admin/category')->with('msg','Category Update successfully..!!');
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
            $categories_id = explode(',',$service->category_id);
            if (($key = array_search($id, $categories_id)) !== false)
            {
                return response(['success' => false , 'data' => "This category's service connected with Appointment first delete appointment"]);
            }
        }

        $id = Category::find($id);
        if($id->image != 'noimage.jpg')
        {
            \File::delete(public_path('images/upload/'.$id->image));
        }
        $id->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Category::find($request->id);
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

    public function update_service_setting()
    {
        $id = Setting::first();
        if($id->service_at_home == 0)
        {
            $id->service_at_home = 1;
        }
        else
        {
            $id->service_at_home = 0;
        }
        $id->save();
        return response(['success' => true]);
    }
}
