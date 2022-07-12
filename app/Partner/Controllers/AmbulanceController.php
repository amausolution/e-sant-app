<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Partner\Models\Ambulance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AmbulanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Partner/Ambulance/Index',[
            'title'=>__('Listing Ambulance'),
            'ambulances'=>Ambulance::all(),
            'options'=> ['A','B','C']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Partner/Ambulance/Create',[
            'title'=>__('Add New Ambulance')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $data = \Illuminate\Support\Facades\Request::all();
       // dd($data);
        \Illuminate\Support\Facades\Request::validate([
            'matricule'=>'required',
            'brand'=>'required',
            'year'=>'numeric:min:1990',
        ],[]);

        $dataInsert = [
           'brand'=>$data['brand'],
           'matricule'=>$data['matricule'],
           'year'=>$data['year'],
           'acquisition_date'=>Carbon::parse($data['acquisition_date'])->format('y-m-d'),
           'class'=>$data['class'],
           'giver'=>$data['giver'],
            'status'=>$data['status']?1:0,
           'partner_id'=>getPartner()->id
        ];

        Ambulance::create($dataInsert);
        return  redirect()->route('ambulance.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response|string
     */
    public function update(Request $request, $id)
    {
        $car = Ambulance::find($id);
        if (!$car){
            return 'no data';
        }
        $data = \Illuminate\Support\Facades\Request::all();
        // dd($data);
        \Illuminate\Support\Facades\Request::validate([
            'matricule'=>'required',
            'brand'=>'required',
            'year'=>'numeric:min:1990',
        ],[]);

        $dataUpdate = [
            'brand'=>$data['brand'],
            'matricule'=>$data['matricule'],
            'year'=>$data['year'],
            'acquisition_date'=>Carbon::parse($data['acquisition_date'])->format('y-m-d'),
            'class'=>$data['class'],
            'giver'=>$data['giver'],
            'status'=>$data['status']?1:0,
        ];
     //  dd($dataUpdate);
        $car->update($dataUpdate);
        return  redirect()->route('ambulance.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $car = Ambulance::find($id);
        if (!$car){
            return 'no data';
        }
        $car->delete();
        return redirect()->back();
    }

    public function updateStatus($id)
    {
      $data =  \Illuminate\Support\Facades\Request::only('status','idA');

      \Illuminate\Support\Facades\Request::validate(['status'=>'required|boolean']);
      $car = Ambulance::find($id);
      if (!$car){
          return 'no data';
      }
      $status = $data['status']?1:0;
      $car->update(['status'=>$status]);
      return redirect()->back();
    }
}
