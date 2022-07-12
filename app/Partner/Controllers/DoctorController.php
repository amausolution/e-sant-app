<?php
namespace App\Partner\Controllers;

use App\Http\Controllers\RootPartnerController;
use App\Http\Resources\DoctorCollection;
use App\Partner\Models\Appointment;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\FegguProfession;
use Feggu\Core\Partner\Models\FegguSpecialization;
use Feggu\Core\Partner\Models\PartnerPermission;
use Feggu\Core\Partner\Models\PartnerRole;
use Feggu\Core\Partner\Models\PartnerUser;
use Feggu\Core\Partner\Partner;
use Illuminate\Queue\NullQueue;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Validator;

class DoctorController extends RootPartnerController
{
    public $permissions;
    public $roles;
    public $departments;
    public $specialities;
    public function __construct()
    {
        parent::__construct();
        $this->permissions = PartnerPermission::get()->map->only('name', 'id');
        $this->roles       = PartnerRole::get()->map->only('id','name');
    }

    public function resetPW($id)
    {

    }
    public function index()
    {

       return Inertia::render('Partner/Doctor/Index',[
           'title'=> __('Listing Doctor'),
           'doctors'=> PartnerUser::where('group',1)
               ->orderByName()
               ->filter(Request::only('search','identifier','job'))
               ->paginate(20)
               ->withQueryString()
               ->through(fn ($user) => [
                   'id' => $user->id,
                   'name' => $user->name,
                   'job'=>$user->job->title,
                   'matricule'=>$user->matricule,
                   'avatar'=>asset($user->getAvatar()),

               ]),
           'filters' => \request()->all('search','identifier','job'),
           'professions'       => FegguProfession::all(),
           ]);
    }
    public function create()
    {
        return Inertia::render('Partner/Doctor/Create',
            [
                'title' => au_language_render('Add New'),
                'roles'             => $this->roles,
                'permissions'       => $this->permissions,
                'user'              => [],
                'departments'       => FegguPartner::where('id',session('partnerId'))->first()->departments()->get()->map->only('id','department'),
                'specialities'      => FegguSpecialization::get()->map->only('id','title'),
                'professions'       => FegguProfession::get()->map->only('id','title'),
                'genders'           => genders(),
                'defaultAvatar'     => asset('images/avatar.png')
            ]
        );
    }
    public function store()
    {
        $data = Request::all();
        //dd($data);
        $dataOrigin = Request::all();
       Request::validate( [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
           // 'username' => 'required|regex:/(^([0-9A-Za-z@\._-]+)$)/|unique:"'.PartnerUser::class.'",username|string|max:100|min:3',
            'avatar'   => 'nullable|image|max:1024|mimes:jpg,jpeg,png,webp',
           // 'password' => 'required|string|max:60|min:8|confirmed',
            'email'    => 'required|string|email|max:255',
            'address'    => 'required|string|max:255',
           // 'matricule'=>'required',
            'gender'=>'required',
            'birthday'=>'required',
            'profession'=>'required',
            'phone'=>'required',
          //  'department'=>'required',
            'education'=>'required',
            'specialities'=>'required',
        ],[
            'first_name.required'=>au_language_render('the first name is required')
        ]);



        $data = au_clean($data,[]);
        $two_char = substr($data['first_name'], 0,2);
        $one_char = $data['last_name'][0];
        $mat = $two_char.'-'.$one_char.session('partnerId').'-'.mt_rand(0000,9999);
        //dd($mat);
        $dataInsert = [
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'address'        => $data['address'],
            'gender'         => $data['gender']['id'],
            'profession'     => $data['profession']['id'],
            'education'      => $data['education'],
            'birthday'       => date('Y/m/d', strtotime($data['birthday']) ),
            'matricule'      => strtoupper($mat),
            'phone'          => $data['phone'],
            'username'       => strtolower($mat),
            'email'          => strtolower($data['email']),
            'password'       => bcrypt($data['password']),
        ];
        if ($image = Request::file('avatar')) {
             $imageDestinationPath = 'data/partner/';
            $postImage =$imageDestinationPath.$data['first_name'].'_'.$data['last_name'].'_'.$data['username'] . "." . $image->getClientOriginalExtension();
            $image->move($imageDestinationPath, $postImage);
            $dataInsert['avatar'] = $postImage;
          // $dataInsert['avatar'] = uploadImage($image, imagePath()['partber']['path'],imagePath()['product']['size'], null ,imagePath()['product']['thumb']);
           // ;
        }
       //dd($data['specialities']);

     //  dd($arraySpeciality);
        $doc = PartnerUser::createUser($dataInsert);

        $specialities = $data['specialities'] ?? [];
        $department = $data['department'] ?? [];
        $arraySpeciality = [];
        $arrayDepart = [];
        foreach ($data['specialities'] as $v){
            $arraySpeciality [] = $v['id'];
        }
        foreach ($data['department'] as $v){
            $arrayDepart [] = $v['id'];
        }
        //Insert roles
        if ($specialities) {
            $doc->specializations()->attach($arraySpeciality);
        }
        //Insert permission
        if ($department) {
            $doc->areDepartments()->attach($arrayDepart);
        }
           $roles = $data['role'] ?? [];
           $permission = $data['permissionUser'] ?? [];

           //Process role special
           if (in_array(1, $roles, true)) {
               // If group admin
               $roles = [1];
               $permission = [];
           }
           //End process role special

           //Insert roles
           if ($roles) {
               $doc->roles()->attach($roles);
           }
           //Insert permission
           if ($permission) {
               $doc->permissions()->attach($permission);
           }
        return redirect()->route('doctor.index')->with('success','Post created successfully.');
    }
    public function edit($id)
    {
        $doctor = PartnerUser::find($id);
        return Inertia::render('Partner/Doctor/Edit',[
            'title'            =>  au_language_render('Edit Doctor'),
            'roles'             => $this->roles,
            'permissions'       => $this->permissions,
            'user' => [
                'id' => $doctor->id,
                'avatar' => asset($doctor->getAvatar()),
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
                'gender' => ['id'=>$doctor->gender,
                'title'=>gender()[$doctor->gender]],
                'birthday' => $doctor->birthday,
                'matricule' => $doctor->matricule,
                // 'email' => $doctor->matricule,
                'username'=>$doctor->username,
                'profession'=>$doctor->job()->get()->map->only('id','title'),
                'phone'=>$doctor->phone,
                'email' => $doctor->email,
                'specialisations' => $doctor->specializations()->get()->map->only('id', 'title'),
                'education' => $doctor->education,
                'title' => $doctor->title,
                'roles' => $doctor->roles()->pluck('id'),
                'permissions' => $doctor->permissions()->pluck('id'),
                'departments' => $doctor->areDepartments()->get()->map->only('id','department'),
                'address' => $doctor->address,
            ],
            'departments'       => FegguPartner::where('id',session('partnerId'))->first()->departments()->get()->map->only('id','department'),
            'specialities'      => FegguSpecialization::get()->map->only('id','title'),
            'professions'       => FegguProfession::get()->map->only('id','title'),
            'genders'           => genders(),
            'defaultAvatar'     => asset('images/avatar.png')
        ]);
    }
    public function update($id)
    {

        $doc = PartnerUser::find($id);
        if (!$doc){
            return 'no data';
        }
        $data = Request::all();
       //dd($data);
        $dataOrigin = Request::all();
      //  dd($data['gender']);
        Request::validate( [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            // 'username' => 'required|regex:/(^([0-9A-Za-z@\._-]+)$)/|unique:"'.PartnerUser::class.'",username|string|max:100|min:3',
            'avatar'   => 'nullable|image|max:1024|mimes:jpg,jpeg,png,webp',
            // 'password' => 'required|string|max:60|min:8|confirmed',
            'email'    => 'required|string|email|max:255',
            'address'    => 'required|string|max:255',
            // 'matricule'=>'required',
            'gender'=>'required',
            'birthday'=>'required',
            'profession'=>'required',
            'phone'=>'required',
            //  'department'=>'required',
            'education'=>'required',
            'specialities'=>'required',
        ],[
            'first_name.required'=>au_language_render('the first name is required')
        ]);

        $dataUpdate = [
            'first_name'     => $data['first_name'],
            'last_name'     => $data['last_name'],
            'address'     => $data['address'],
            'gender'     => $data['gender']['id'],
            'profession'     => $data['profession'][0]['id'],
            'education'     => $data['education'],
            'birthday'     => date('Y/m/d', strtotime($data['birthday']) ),
            'matricule'     => $data['matricule'],
            'phone'     => $data['phone'],
            'username' => strtolower($data['username']),
            'email'    => strtolower($data['email']),
            //'password' => bcrypt($data['password']),
        ];
        if ($image = request()->file('avatar')) {
            $imageDestinationPath = 'data/partner/';
            $postImage =$imageDestinationPath.$doc->first_name.'_'.$doc->last_name."_".$doc->username. "." . $image->getClientOriginalExtension();
            $image->move($imageDestinationPath, $postImage);
            \File::delete(public_path($doc->avatar));
            $dataUpdate['avatar'] = $postImage;
        }
        //dd($dataUpdate);
        PartnerUser::updateInfo($dataUpdate, $id);
        $specialities = $data['specialities'] ?? [];
        $department = $data['department'] ?? [];
        $doc->areDepartments()->detach();
        $doc->specializations()->detach();
        $arraySpeciality = [];
        $arrayDepart = [];
        foreach ($data['specialities'] as $v){
            $arraySpeciality [] = $v['id'];
        }
        foreach ($data['department'] as $v){
            $arrayDepart [] = $v['id'];
        }
        //Insert speciality
        if ($specialities) {
            $doc->specializations()->attach($arraySpeciality);
        }
        //Insert depart
        if ($department) {
            $doc->areDepartments()->attach($arrayDepart);
        }
             $roles = $data['role'] ?? [];
             $permission = $data['permissionUser'] ?? [];
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
        return redirect()->route('doctor.index')->with('success', au_language_render('partner.user.edit_success'));

    }
    public function delete()
    {
        $idD = Request::only('id');
        $doctor = PartnerUser::where('id',$idD)->first();
        if (!$doctor){
            return 'no data';
        }
       // dd($doctor);
        if (!empty($doctor->avatar)){
            \File::delete(public_path($doctor->avatar));
        }
        $doctor->delete();

        return redirect()->route('doctor.index')->with('success', au_language_render('partner.user.edit_success'));
    }

    public function show($id,Request $request)
    {
        $doctor = PartnerUser::find($id);
        $data = Appointment::query()->get(['id', 'time','date', 'note'])->toArray();
        return inertia('Partner/Doctor/Show',[
            'title' => au_language_render('partner.doctor.profile'),
            'subTitle' => au_language_render('profile_doctor'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'doctor' => $doctor,
            'data'=>$data,
        ]);
    }
    public function daily_scheduling()
    {
        $id = Partner::user()->id;
        $doctor = PartnerUser::find($id);

        return view($this->templatePathPartner.'doctor.daily_scheduling',[
            'title' => au_language_render('partner.doctor.profile'),
            'subTitle' => au_language_render('profile_doctor'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'doctor' => $doctor,
        ]);
    }

    public function event(Request $request)
    {
        if($request->ajax())
        {
            $data = Appointment::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);
            return response()->json($data);
        }
        return view('full-calender');
    }

    public function action(Request $request)
    {
        dd($request->type);
        /*if($request->type == 'delete')
        {
            $event = PartnerUser::find($request->id)->delete();

            return response()->json($event);
        }*/
    }

    public function profile(Request $request)
    {
        $doctor = PartnerUser::find(Partner::user()->id);
        $data = Appointment::query()->get(['id', 'title','start', 'end'])->toArray();
        return view($this->templatePathPartner.'doctor.doctor_about',[
            'title' => au_language_render('partner.doctor.profile'),
            'subTitle' => au_language_render('profile_doctor'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'doctor' => $doctor,
            'data'=>$data,
        ]);
    }

    public function appointment()
    {

    }
}
