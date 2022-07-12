<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Partner\Models\Operation;
use App\Partner\Models\OperationTask;
use Carbon\Carbon;
use Feggu\Core\Partner\Models\FegguUser;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       return Inertia::render('Partner/Operation/Index',[
           'title'=> __('Programming Operation List'),
           'filters' => \request()->all('search', 'trashed','gender','identifier'),
           'operations' => Operation::where('partner_id',session('partnerId'))
               ->whereDate('created_at', '<=', date('Y-m-d'))
               ->where('status',0)
               ->filter(Request::only('search', 'trashed','gender','identifier'))
               ->paginate(20)
               ->withQueryString()
               ->through(fn ($operation) => [
                   'id' => $operation->id,
                   'type' => $operation->type,
                   'status' => $operation->status,
                   'date_programming'=>showDateTime($operation->date_programming),
                   'patient' => $operation->patient ? $operation->patient->only('name','doc_number','phone','gender') : null,
               ]),
       ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Partner/Operation/Create',[
            'title'=> __('Programming Operation'),
        ]);
    }

    /**
     * check matricule
     */
    public function check()
    {
        $data = Request::only('matricule');
        Request::validate(['matricule'=>'required|exists:Feggu\Core\Partner\Models\FegguUser,doc_number'],[
            'matricule.exists'=>__('Identifier not valid'),
            'matricule.required'=>__('Identifier is empty')
        ]);
        session()->flash('flash.type', 'success');
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = Request::only('matricule','type_operation','date_programming','note');
        Request::validate([
            'matricule'=>'required|exists:Feggu\Core\Partner\Models\FegguUser,doc_number',
            'type_operation'=>'required|string|max:200',
            'date_programming'=>'required|after_or_equal:' . date('Y-m-d H:i:s'),
            'note'=>'nullable|string'
        ],[
            'matricule.exists'=>__('Identifier not valid'),
            'matricule.required'=>__('Identifier is empty')
        ]);
        //dd($data);
        $patient = FegguUser::where('matricule',$data['matricule'])->orWhere('doc_number',$data['matricule'])->first();

        $dataOperation = [
           'patient_id'=>$patient->id,
            'partner_id'=>getPartner()->id,
            'type'=>$data['type_operation'],
            'date_programming'=>Carbon::parse($data['date_programming'])->format('y-m-d h:i:s'),
            'note'=>$data['note'],
            'user_id'=>\Partner::user()->id
        ];
        $dataOperation = au_clean($dataOperation,[]);
       // dd($dataOperation);
        Operation::create($dataOperation);
        return redirect()->route('operation.index');
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
     * @return Response
     */
    public function edit($id)
    {
        $operation = Operation::findOrFail($id);
        return Inertia::render('Partner/Operation/Edit',[
            'title' => __('Edit Operation'),
            'operation'=>[
             'id'=> $operation->id,
             'type_operation'=>$operation->type,
             'note'=>$operation->note ? au_html_render($operation->note):null,
             'date_programming'=>$operation->date_programming,
             'tasks'=>$operation->tasks()->orderBy('sort','asc')->get()->map->only('status','title','id','sort'),
             'patient'=> [
                 'name'=>$operation->patient->name,
                 'age'=> showAge($operation->patient->birthday),
                 'phone'=>$operation->patient->mobil,
                 'group_blood'=>$operation->patient->blood_group,
                 'allergies'=>$operation->patient->allergies()->get()->map(function ($allergy){
                     return [
                         'allergy'=>$allergy->allergy,
                         'result'=>$allergy->result,
                         'observation'=>$allergy->observation,
                         'date'=>$allergy->created_at
                     ];
                 }),
                 'pathologies'=>$operation->patient->pathologies()->get()->map(function ($allergy){
                     return [
                         'pathology'=>$allergy->pathology,
                         'level'=>$allergy->level,
                         'observation'=>$allergy->observation,
                         'date'=>$allergy->created_at
                     ];
                 })
             ],
            ]
        ]);
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
        //
    }

    public function task()
    {
      $data =  Request::only('title','operationId');
      Request::validate([
          'title'=>'required|max:100',
          'operationId'=>'required'
      ],[]);
      $dataTask = [
          'title'=>$data['title'],
          'operation_id'=>$data['operationId']
      ];

      $dataTask = au_clean($dataTask,[], true);
      OperationTask::create($dataTask);
      return redirect()->back();
    }

    public function done($id)
    {
      $task =  OperationTask::findOrFail($id);
      $dataUpdate= [
          'status'=>1,
          'do_at'=> date(now()),
          'do_by'=>\Partner::user()->id
      ];
    //  dd($dataUpdate);
      $task->update($dataUpdate);
      return redirect()->back();
    }
    public function sortTask($id)
    {
      $data = Request::only('sort');
      Request::validate(['sort'=>'required|numeric|min:0']);
      $task =  OperationTask::findOrFail($id);
     // dd($data);
      $task->update(['sort'=>$data['sort']]);
      return redirect()->back();
    }

    public function deleteTask($id)
    {
        OperationTask::findOrFail($id)->delete();
        return redirect()->back();
    }
}
