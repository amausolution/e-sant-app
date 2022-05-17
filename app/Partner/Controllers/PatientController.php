<?php


namespace App\Partner\Controllers;


use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\FegguUser;
use Feggu\Core\Partner\Models\PartnerUser;
use Feggu\Core\Partner\Models\PatientAllergy;
use Feggu\Core\Partner\Models\PatientPathology;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;


class PatientController extends RootPartnerController
{
    public $groupBlood;
    public $departments;
    public function __construct()
    {
        parent::__construct();
        $this->groupBlood = DB::table(AU_DB_PREFIX.'feggu_blood')->pluck('blood');
    }
    public function index()
    {
        $partnerId = session('partnerId');
        if (!$partnerId){
            abort(404);
        }
        return view($this->templatePathPartner.'screen.list_patient',[
            'title'=>au_language_render('partner.patient.list'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'title_form' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . au_language_render('partner.menu.create'),
        ]);
    }

    public function postPathology()
    {
        $data = request()->all();
        $data = au_clean($data,[]);

        $dataInsert = [
            'hospital_id' => session('partnerId'),
            'patient_id'=>$data['patient_id'],
            'observation'=>$data['observation'],
            'level'=>$data['level']??null,
            'pathology'=>$data['pathology'],
            'doctor_id'=>\Partner::user()->id
        ];
        PatientPathology::create($dataInsert);
        return redirect()->back();
    }
    public function postAllergy()
    {
        $data = request()->all();
        $data = au_clean($data,[]);

        $dataInsert = [
            'hospital_id' => session('partnerId'),
            'patient_id'=>$data['patient_id'],
            'observation'=>$data['observation'],
            'allergy'=>$data['allergy'],
            'doctor_id'=>\Partner::user()->id
        ];
        PatientAllergy::create($dataInsert);
        return redirect()->back();
    }

    public function create()
    {
        $part =  FegguPartner::where('id',session('partnerId'))->first();
        $data = [
            'title' => au_language_render('admin.banner.add_new'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'patient' => [],
            'bloods'       => $this->groupBlood,
            'url_action'        => au_route_partner('partner_patient.create'),
        ];
        return view($this->templatePathPartner.'screen.patient.patients',[
            'data'=>$data,
            'departments'=>$part,
        ]);
    }

    public function store()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.PartnerUser::class.'",username|string|max:100|min:3',
            'avatar'   => 'nullable|image|max:1024|mimes:jpg,jpeg,png',
            'password' => 'required|string|max:60|min:8|confirmed',
            'email'    => 'required|string|email|max:255',
            'address'    => 'required|string|max:255',
            'matricule'=>'required',
            'gender'=>'required|numeric',
            'birthday'=>'required|date',
            'profession'=>'required|numeric',
            'phone'=>'required|numeric',
            'department'=>'required',
            'education'=>'required',
            'specialization'=>'required',
        ],[
            'first_name.required'=>au_language_render('partner.validation.first_name_required')
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($image = request()->file('avatar')) {
            $imageDestinationPath = 'data/partner/';
            $postImage =$imageDestinationPath.date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($imageDestinationPath, $postImage);
            //$input['image'] = "$postImage";
        }

        $dataInsert = [
            'first_name'     => $data['first_name'],
            'last_name'     => $data['last_name'],
            'address'     => $data['address'],
            'gender'     => $data['gender'],
            'profession'     => $data['profession'],
            'education'     => $data['education'],
            'birthday'     => date('Y/m/d', strtotime($data['birthday']) ),
            'matricule'     => $data['matricule'],
            'phone'     => $data['phone'],
            'username' => strtolower($data['username']),
            'avatar'   =>$postImage,
            'email'    => strtolower($data['email']),
            'password' => bcrypt($data['password']),
        ];

        $doc = PartnerUser::createUser($dataInsert);

        $specialities = $data['specialization'] ?? [];
        $department = $data['department'] ?? [];
        //Insert roles
        if ($specialities) {
            $doc->specializations()->attach($specialities);
        }
        //Insert permission
        if ($department) {
            $doc->departments()->attach($department);
        }
        /*   $roles = $data['roles'] ?? [];
           $permission = $data['permission'] ?? [];

           //Process role special
           if (in_array(1, $roles)) {
               // If group admin
               $roles = [1];
               $permission = [];
           } elseif (in_array(2, $roles)) {
               // If group onlyview
               $roles = [2];
               $permission = [];
           }
           //End process role special

           //Insert roles
           if ($roles) {
               $user->roles()->attach($roles);
           }
           //Insert permission
           if ($permission) {
               $user->permissions()->attach($permission);
           }*/

        return redirect()->route('partner_doctor.index')->with('success','Post created successfully.');
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $doctor = PartnerUser::find($id);
        $data = [
            'title' => au_language_render('admin.banner.add_new'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'doctor' => $doctor,
            'url_action'        => au_route_partner('partner_doctor.edit', ['id' => $doctor['id']]),
            'roles'             => $this->roles,
            'permissions'       => $this->permissions,
        ];
        return view($this->templatePathPartner.'screen.doctor.doctor',['data'=>$data]);
    }


    /**
     * update status
     */
    public function update($id)
    {
        $doc = PartnerUser::find($id);
        if (!$doc){
            return 'no data';
        }
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.PartnerUser::class.'",username,' . $doc->id . '",username|string|max:100|min:3',
            'avatar'   => 'nullable|image|max:1024|mimes:jpg,jpeg,png',
            'password' => 'nullable|string|max:60|min:8|confirmed',
            'email'    => 'required|string|email|max:255',
            'address'    => 'required|string|max:255',
            'matricule'=>'required',
            'gender'=>'required|numeric',
            'birthday'=>'nullable',
            'profession'=>'required|numeric',
            'phone'=>'required|numeric',
            'department'=>'required',
            'education'=>'required',
            'specialization'=>'required',
        ],[
            'first_name.required'=>au_language_render('partner.validation.first_name_required')
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($image = request()->file('avatar')) {
            $imageDestinationPath = 'data/partner/';
            $postImage =$imageDestinationPath.date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($imageDestinationPath, $postImage);
            $dataUpdate['avatar'] = "$postImage";
        }

        $dataUpdate = [
            'first_name'     => $data['first_name'],
            'last_name'     => $data['last_name'],
            'address'     => $data['address'],
            'gender'     => $data['gender'],
            'profession'     => $data['profession'],
            'education'     => $data['education'],
            'birthday'     => date('Y/m/d', strtotime($data['birthday']) ),
            'matricule'     => $data['matricule'],
            'phone'     => $data['phone'],
            'username' => strtolower($data['username']),

            'email'    => strtolower($data['email']),
            'password' => bcrypt($data['password']),
        ];
        //dd($dataUpdate);
        PartnerUser::updateInfo($dataUpdate, $id);
        $specialities = $data['specialization'] ?? [];
        $department = $data['department'] ?? [];
        $doc->departments()->detach();
        $doc->specializations()->detach();
        //Insert roles
        if ($specialities) {
            $doc->specializations()->attach($specialities);
        }
        //Insert permission
        if ($department) {
            $doc->departments()->attach($department);
        }
        /* if (!in_array($doc->id, AU_GUARD_PARTNER)) {
             $roles = $data['roles'] ?? [];
             $permission = $data['permission'] ?? [];
             $doc->roles()->detach();
             $doc->permissions()->detach();
             //Insert roles
             if ($roles) {
                 $doc->roles()->attach($roles);
             }
             //Insert permission
             if ($permission) {
                 $doc->permissions()->attach($permission);
             }
         }*/

        return redirect()->route('partner_doctor.index')->with('success', au_language_render('partner.user.edit_success'));
    }

    /*
    Delete list Item
    Need mothod destroy to boot deleting in model
     */
    public function delete()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => au_language_render('admin.method_not_allow')]);
        } else {
            $id = request('id');
            $check = PartnerMenu::where('parent_id', $id)->count();
            if ($check) {
                return response()->json(['error' => 1, 'msg' => au_language_render('admin.menu.error_have_child')]);
            } else {
                PartnerMenu::destroy($id);
            }
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /*
    Update menu resort
     */
    public function updateSort()
    {
        $data = request('menu') ?? [];
        $reSort = json_decode($data, true);
        $newTree = [];
        foreach ($reSort as $key => $level_1) {
            $newTree[$level_1['id']] = [
                'parent_id' => 0,
                'sort' => ++$key,
            ];
            if (!empty($level_1['children'])) {
                $list_level_2 = $level_1['children'];
                foreach ($list_level_2 as $key => $level_2) {
                    $newTree[$level_2['id']] = [
                        'parent_id' => $level_1['id'],
                        'sort' => ++$key,
                    ];
                    if (!empty($level_2['children'])) {
                        $list_level_3 = $level_2['children'];
                        foreach ($list_level_3 as $key => $level_3) {
                            $newTree[$level_3['id']] = [
                                'parent_id' => $level_2['id'],
                                'sort' => ++$key,
                            ];
                        }
                    }
                }
            }
        }
        $response = (new PartnerMenu)->reSort($newTree);
        return $response;
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        //dd($id);
        $patient = FegguUser::where('id',$id)->first();
        // dd($patient);
        return view($this->templatePathPartner.'screen.patient.profile_patient',[
            'title' => au_language_render('partner.patient.profile'),
            'subTitle' => au_language_render('profile_patient'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'patient' => $patient,
        ]);
    }
    public function Consultation($id)
    {
        //dd($id);
        $consultation = FegguConsultation::with(['prescriptions','analyses','doctor','hospital','patient'])->where('id',$id)->first();
        // dd($consultation);
        return view($this->templatePathPartner.'screen.patient.consultation_patient',[
            'title' => au_language_render('partner.patient.profile'),
            'subTitle' => au_language_render('profile_patient'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'consultation' => $consultation,
        ]);
    }
    public function showConsultation($id)
    {
        //dd($id);
        $consultation = FegguConsultation::with(['prescriptions','analyses'])->where('id',$id)->first();
        // dd($consultation);
        return view($this->templatePathPartner.'screen.patient.show_consultation',[
            'title' => au_language_render('partner.patient.consultation'),
            'subTitle' => au_language_render('profile_patient'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'consultation' => $consultation,
        ]);
    }
}
