<?php

namespace App\Http\Controllers\Employee;

use App\Models\CoworkerPortfolio;
use App\Models\Coworkers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Spatie\Permission\Models\Permission;

class EmployeePortFolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Permission::create(['name' => 'coworker_review']);
        abort_if(Gate::denies('coworker_portfolio_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $worker = Coworkers::where('user_id',auth()->user()->id)->first();
        $portfolios = CoworkerPortfolio::where('coworker_id',$worker->id)->get();
        return view('coworker.coworker portfolio.portfolio',compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('coworker.coworker portfolio.create_portfolio');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $coworker = Coworkers::where('user_id',auth()->user()->id)->first();
        $data = $request->all();
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = public_path() . '/images/upload';
        $file->move($path, $fileName);
        $data['coworker_id'] = $coworker->id;
        $data['image'] = $fileName;
        $id = CoworkerPortfolio::create($data);
        // return true;
        return response()->json(['success'=>$fileName]);
        // return redirect('coworker/portfolio');
        // return redirect()->to('coworker/portfolio');

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = CoworkerPortfolio::find($id);
        $id->delete();
        return response(['success' => true]);
    }
}
