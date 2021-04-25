<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Service;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('offer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $offers = Offer::all();
        $categories = Category::where('status',1)->get();
        $services = Service::where('status',1)->get();
        return view('admin.offer.offer',compact('categories','offers','services'));
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
            'code' => 'required',
            'discount' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'service_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
            'type' => 'required',
        ]);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate([
                'image' => 'mimes:png,jpg,jpeg',
            ]);
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        $data['category_id'] = implode(',',$data['category_id']);
        $data['service_id'] = implode(',',$data['service_id']);
        Offer::create($data);
        return redirect('admin/offer')->with('msg' , 'Offer created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Offer::find($id);
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
        $data = Offer::find($id);
        $data['cat_id'] = explode(',',$data->category_id);
        $data['serv_id'] = explode(',',$data->service_id);
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
        $id = Offer::find($id);
        $data = $request->all();
        $request->validate([
            'code' => 'required',
            'discount' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'service_id' => 'required',
        ]);
        $data['category_id'] = implode(',',$data['category_id']);
        $data['service_id'] = implode(',',$data['service_id']);
        if ($file = $request->hasfile('image'))
        {
            $request->validate([
                'image' => 'mimes:png,jpg,jpeg',
            ]);
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        $id->update($data);
        return redirect('admin/offer')->with('msg' , 'Offer updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $id = Offer::find($id);
        if($id->image != 'noimage.jpg')
        {
            \File::delete(public_path('images/upload/'.$id->image));
        }
        $id->delete();
        return response(['success' => true]);
    }

    public function offer_category(Request $request)
    {
        $category_ids = $request->category_id;
        $service_ids = [];
        $s = Service::where('status',1)->get();
        foreach ($s as $service)
        {
            foreach ($category_ids as $category_id)
            {
                if(count(array_keys(array_filter(explode(',',$service->category_id)),$category_id))>0)
                {
                    array_push($service_ids,$service->id);
                }
            }
        }
        $services = Service::whereIn('id',$service_ids)->get();
        return response(['success' => true , 'data' => $services]);
    }

    public function update_offer_category(Request $request)
    {
        $category_ids = $request->category_id;
        $service_ids = [];
        $s = Service::where('status',1)->get();
        foreach ($s as $service)
        {
            foreach ($category_ids as $category_id)
            {
                if(count(array_keys(array_filter(explode(',',$service->category_id)),$category_id))>0)
                {
                    array_push($service_ids,$service->id);
                }
            }
        }
        $services = Service::whereIn('id',$service_ids)->get();
        return response(['success' => true , 'data' => $services]);
    }
}
