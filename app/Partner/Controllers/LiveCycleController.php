<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Partner\Models\Born;
use App\Partner\Models\LiveCycle;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPatient;
use Feggu\Core\Partner\Models\FegguUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\ResponseFactory;

class LiveCycleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResponseFactory
     */
    public function index()
    {
        $partnerId = session('partnerId');
        // dd(getPartner()->consultations);
        if (!$partnerId){
            abort(404);
        }
        return inertia('Partner/Live/Index',
        [
            'title'=>__('Partner Patient List'),
            'genders'=> genders(),
            'patients'=> LiveCycle::where('hospital_id',$partnerId)->paginate(20)
                ->withQueryString()->through(fn ($patient)=> [
                    'avatar'=>$patient->patient->getAvatar(),
                    'name'=>$patient->patient->name,
                    'id'=>$patient->patient->slug,
                    'phone'=>$patient->patient->mobil,
                    'birthday'=>showDate($patient->patient->birthday),
                    'doc_number'=>$patient->patient->doc_number,
                    'phone_urgency'=>$patient->patient->phone_urgency,
                    'gender'=>gender()[$patient->patient->gender],
                    'status'=>$patient->status,
                    'godfather'=>$patient->godfather->name,
                    'godfather_phone'=>$patient->godfather->mobil
                ]),
            'filters' => \request()->all('search','identifier','gender'),
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response|ResponseFactory
     */
    public function death()
    {
        $matricule = \Illuminate\Support\Facades\Request::only('identifier');
      //  dd($matricule);
        $pa=[];
        if ($matricule){
            $pa =  FegguUser::query()->where('matricule',$matricule)->get();
        }

       // dd($pa);
        return inertia('Partner/Live/Death',[
            'title'=> __('Register new death'),
            'filters' => \request()->all('identifier'),
            'patient' => $pa,
        ]);
    }
    public function deathPatient($id)
    {
        $patient = FegguUser::where('slug',$id)->first();
       // dd($patient);
        return Inertia::render('Partner/Live/Death',[
            'title'=> __('Register new death'),
            'death'=> [
                'name'=> $patient->name,
                'matricule'=>$patient->doc_number,
                'id'=>$patient->slug
            ],
        ]);
    }

    public function storeDeath()
    {
        $data = \Illuminate\Support\Facades\Request::all();
       // dd($data);
        $patient = FegguUser::where('slug',$data['id']);
        if (!$patient || $patient->live_cycle==0){
            return 'no data';
        }
        \Illuminate\Support\Facades\Request::validate([
            'date'=>'required|date|before:tomorrow',
            'kind_of_death'=>'required|string|max:300',
            'id'=>'required'
        ],[]);
        $dataInsert = [
            'kind_of_death'=>$data['kind_of_death'],
            'note'=>$data['note'],
            'date'=>\Carbon\Carbon::parse($data['date'])->format('y/m/d'),
            'time'=>\Carbon\Carbon::parse($data['date'])->format('h:i'),
            'hospital_id'=>getPartner()->id,
            'user_id'=>\Partner::user()->id
        ];

        $dataInsert = au_clean($dataInsert,[]);
        LiveCycle::create($dataInsert);
        $patient->update(['live_cycle'=>0,'mobil','++++++']);
        return redirect()->route('patients.index');
    }

    public function born()
    {
        return inertia('Partner/Live/Born',[
            'title'=> __('Register new born'),
            'gender'=>genders(),
            'type_childbirth'=>typeChildBirth(),
            'state_baby'=>stateBaby(),
        ]);
    }

    public function storeBorn()
    {
       $data = \Illuminate\Support\Facades\Request::all();
    //   dd($data);
        \Illuminate\Support\Facades\Request::validate([
            'godfather_phone'=>'required|string|regex:/[0-9]{9}/',
            'dob'=>'required|date',
            'gender'=>'required|between:1,2',
            'nbr_baby'=>'required|numeric|min:1',
            'type_childbirth'=>'required',
            'state_baby'=>'required',
            'is_godfather'=>'required',
        ]);
        if ($data['fatherMat']){
            \Illuminate\Support\Facades\Request::validate([
                'mather_matricule'=>'required|exists:'.FegguUser::class.',matricule'
            ]);
        }else{
            \Illuminate\Support\Facades\Request::validate([
                'mather_fname'=>'required|string|max:50',
                'mather_lname'=>'required|string|max:50'
            ]);
        }
        if ($data['matherMat']){
            \Illuminate\Support\Facades\Request::validate([
                'mather_matricule'=>'required|exists:'.FegguUser::class.',matricule'
            ]);
        }else{
            \Illuminate\Support\Facades\Request::validate([
                'mather_fname'=>'required|string|max:50',
                'mather_lname'=>'required|string|max:50',
                'cin'=>'required'
            ]);
        }
       $dataBorn = [
           'note'=>$data['note'],
           'gender'=>$data['gender'],
           'state_baby'=>$data['state_baby'],
           'matricule_doc'=>$data['matricule_doc'],
           'type_childbirth'=>$data['type_childbirth'],
           'godfather_phone'=>$data['godfather_phone'],
           'dob'=>\Carbon\Carbon::parse($data['dob'])->format('y-m-d'),
           'nbr_baby'=>$data['nbr_baby'],
           'father_fname'=>$data['father_fname'],
           'father_lname'=>$data['father_lname'],
           'mather_fname'=>$data['mather_fname'],
           'mather_lname'=>$data['mather_lname'],
           'father_matricule'=>$data['father_matricule'],
           'mather_matricule'=>$data['mather_matricule'],
       ];
       $last_name ='';
       $first_name ='';
       $cin ='';
       $gf=null;
       $gId='';
       if ($data['father_matricule']){
           $gf = FegguUser::where('matricule', $data['father_matricule'])->first();
           $last_name = $gf->last_name;
           $first_name = $gf->first_name;
           $gId = $gf->id;
           $cin =$gf->cin;
       }else{
           $last_name = $data['father_lname'];
           $first_name = $data['father_fname'];
           $cin=$data['cin'];
       }
       //dd($dataBorn);
        $dataBorn = au_clean($dataBorn,[]);
        Born::create($dataBorn);
       $dataNew = [
           'birthday'=>\Carbon\Carbon::parse($data['dob'])->format('y-m-d'),
           'gender'=>$data['gender'],
           'address'=>$data['address'],
           'phone2'=>$data['godfather_phone'],
           'phone_urgency'=>$data['godfather_phone'],
           'partner_id'=>getPartner()->id,
           'godfather_name'=> $first_name.' '.$last_name,
           'godfather_cin'=> $cin,
           'last_name'=>$last_name,
       ];
        if ($data['father_matricule']){
            $dataNew['godfather_id']=$gId;
        }

        $dataNew = au_clean($dataNew,[]);
        $user = FegguUser::create($dataNew);
        $doc = generateDocNumber('xxx',$last_name,$user->id);
        $user->update(['doc_number'=>$doc,'matricule'=>$doc]);

        return redirect()->route('live.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
