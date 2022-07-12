<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use App\Partner\Models\Laboratory;
use App\Partner\Models\LabTypeAnalyse;
use Feggu\Core\Partner\Models\CategoryAnalysisDetail;
use Feggu\Core\Partner\Models\FegguAnalyseResult;
use Feggu\Core\Partner\Models\FegguConsultationAnalyse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AnalyseController extends RootPartnerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function postDepart()
    {
        Validator::make([
            'depart'=>'required',
            'room'=>'nullable|numeric|min:1',
        ],[]);

        session(['departmentIds'=>\request()->depart]);
        session(['room'=>\request()->room]);
        $notify[] = ['success', 'Brand Created Successfully'];
        return redirect()->route('consultation.index')->withNotify($notify);
    }

    public function index()
    {
       return Inertia::render('Laboratory/Analyse/Index',[
           'title'=> __('Search Analyse'),
           'filters' => \request()->all('search','matricule'),
           'type_partner'=>getPartner()->laboratory->typeAnalyses()->where('status',1)->pluck('analyse'),
           'analyses'=> FegguConsultationAnalyse::whereNull('deleted_at')
               ->filter(\Illuminate\Support\Facades\Request::only('search', 'matricule'))
               ->paginate(10)
               ->withQueryString()
               ->through(fn ($analyse) => [
                    'id'=>$analyse->id,
                    'analyse_id'=>$analyse->slug,
                    'price'=>$analyse->price,
                    'analyse'=>json_decode($analyse->analyse,true)['title'],
                    'id_type'=>json_decode($analyse->analyse,true)['id'],
                    'note'=>$analyse->note,
                    'status'=>$analyse->status,
                    'name'=>$analyse->consultation ? $analyse->consultation->patient->name:null,
                    'phone'=>$analyse->consultation ? $analyse->consultation->patient->mobil:null,
                    //'address'=>$analyse->consultation->address ?? $analyse->consultation->patient->address,
                    'emergency'=>$analyse->emergency,
                    'doctor'=>$analyse->doctor->matricule,
                    'date'=> showDate($analyse->created_at),
               ])
       ]);
    }

    public function edit($id)
    {
          $analyse =  FegguConsultationAnalyse::find($id);
            if (!$analyse){
                return 'no data';
            }
         $isan = json_decode($analyse->analyse, true)  ;
         $typeP = getPartner()->laboratory->typeAnalyses()->where('analyse',$isan['id'])->get()->map->only('price','take_assurance','duration','status')->first();
            //dd($typeP);

         return  Inertia::render('Laboratory/Analyse/Edit',[
              'title' => __('Analyse'),
              'typeAnalyse'=>$typeP,
              'analyse'=> [
                  'id'=> $id,
                  'slug'=> $analyse->slug,
                  'analyse'=>json_decode($analyse->analyse,true)['title'],
                  'doctors'=>[
                      'name'=> $analyse->doctor->name,
                      'matricule'=>$analyse->doctor->matricule
                  ]
              ]
         ]);
    }
    public function show($id)
    {
          $analyse =  FegguConsultationAnalyse::find($id);
            if (!$analyse){
                return 'no data';
            }
         $isan = json_decode($analyse->analyse, true)  ;
         $typeP = getPartner()->laboratory->typeAnalyses()->where('analyse',$isan['id'])->get()->map->only('price','take_assurance','duration','status')->first();
            //dd($typeP);

         return  Inertia::render('Laboratory/Analyse/Show',[
              'title' => __('Analyse'),
              'typeAnalyse'=>$typeP,
              'analyse'=> [
                  'id'=> $id,
                  'slug'=> $analyse->slug,
                  'analyse'=>json_decode($analyse->analyse,true)['title'],
                  'doctors'=>[
                      'name'=> $analyse->doctor->name,
                      'matricule'=>$analyse->doctor->matricule
                  ]
              ]
         ]);
    }

    public function lab()
    {
        return Inertia::render('Laboratory/Settings/Lab',[
            'title'=> __('Settings Laboratory'),
            'categories'=>CategoryAnalysisDetail::get()->map->only('id','title'),
            'departments'=>getPartner()->departments()->get()->map->only('id','department'),
            'analyses'=> LabTypeAnalyse::filter(\Illuminate\Support\Facades\Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($analyse) => [
                    'id'=>$analyse->id,
                    'analyse'=>$analyse->category->title,
                    'price'=>$analyse->price,
                    'take_assurance'=> $analyse->take_assurance,
                    'status'=>$analyse->status,
                    'duration'=>$analyse->duration
                ]),
        ]);
    }

    public function store()
    {
        $data = Request::all();
      //  dd($data);
        Request::validate([
            'analyse'=>'required|unique:'.LabTypeAnalyse::class.',laboratory_id,null,id,analyse,'.$data['analyse'],
            'price'=>'required|numeric',
            'department'=>'required',
        ],[]);
        $lab = Laboratory::where('partner', session('partnerId'))->first();
         //dd($data);
        $data = au_clean($data,[]);
        $type = new LabTypeAnalyse();
        $type->analyse = $data['analyse'];
        $type->price = $data['price'];
        $type->department = $data['department'];
        $type->take_assurance = !empty($data['take_assurance'])?1:0;
        $type->status = !empty($data['status'])?1:0;
        $type->duration = $data['duration'];

        $lab->typeAnalyses()->save($type);
        return redirect()->back();
    }

    public function result()
    {
        $data = Request::all();
        $analyse = FegguConsultationAnalyse::find($data['idAnalyse']);
        if (!$analyse){
            return 'no data';
        }
        $dataInsert = [
             'result'=>$data['result'],
             'note'=>$data['note'],
             'labo_id'=>getPartner()->laboratory->id,
             'analyse_id'=>$data['idAnalyse']
        ];
        $dataInsert= au_clean($dataInsert,[]);
        $analyse->update(['status'=>1]);
        FegguAnalyseResult::create($dataInsert);
        return redirect()->route('analyse.index');
    }

    public function delete()
    {
        $data = Request::only('id');
       $lb = LabTypeAnalyse::find($data['id']);
       if (!$lb){
           return 'no data';
       }
       $lb->delete();
        return redirect()->back();
    }

    public function editLabSetting($id)
    {
        $data = Request::all();
       $type = LabTypeAnalyse::find($id);
       if (!$type){
           return 'no data';
       }
      return Inertia::render('Laboratory/Settings/Edit',
        [
            'title'=> __('Settings Laboratory'),
            'categories'=>CategoryAnalysisDetail::get()->map->only('id','title'),
            'departments'=>getPartner()->departments()->get()->map->only('id','department'),
            'analyses'=> LabTypeAnalyse::filter(\Illuminate\Support\Facades\Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($analyse) => [
                    'id'=>$analyse->id,
                    'analyse'=>$analyse->category->title,
                    'price'=>$analyse->price,
                    'take_assurance'=> $analyse->take_assurance,
                    'status'=>$analyse->status,
                    'duration'=>$analyse->duration
                ]),
            'dataAnalyse'=> [
                'analyse'=>$type->category()->get()->map->only('id','title'),
                'price'=>$type->price,
                'id'=>$type->id,
                'duration'=>$type->duration,
                'status'=>$type->status,
                'take_assurance'=>$type->take_assurance,
                'department'=>$type->department()->get()->map->only('id','department'),
            ]
        ]
      );
    }

    public function update()
    {
        $data = Request::all();
        $type = LabTypeAnalyse::find($data['id']);

        if (!$type){
            return 'no data';
        }

       // dd($data['department']['id']);
        Request::validate([
            'analyse'=>'required',
            'department'=>'required',
            'price'=>'required|numeric',
        ],[]);
       $idA= $data['analyse'][0]['id'];
       $idD =$data['department']['id']??'';
        $dataUpdate = [
            'analyse'=> $idA,
            'department_id'=> $idD,
            'price'=> $data['price'],
            'take_assurance'=> !empty($data['take_assurance'])?1:0,
            'status'=> !empty($data['status'])?1:0,
        ];
      //  dd($dataUpdate);
        $dataUpdate = au_clean($dataUpdate,[],true);
        $type->update($dataUpdate);
        return redirect()->route('lab.setting');
    }

    public function config()
    {
        return Inertia::render('Laboratory/Settings/Config');
    }
}
