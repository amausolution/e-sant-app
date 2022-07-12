<?php


namespace App\Partner\Controllers;


use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Admin\Models\AdminConfig;
use Feggu\Core\Admin\Models\AdminPage;
use Feggu\Core\Admin\Models\AdminTemplate;
use Feggu\Core\Partner\Models\FegguCurrency;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\HospitalRoom;
use Feggu\Core\Partner\Models\HospitalRoomBet;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use mysql_xdevapi\Exception;
use Throwable;

class SettingController extends RootPartnerController
{
    public $templates;
    public $currencies;
    public $languages;
    public $timezones;

    public function __construct()
    {
       Parent::__construct();


        foreach (timezone_identifiers_list() as $key => $value) {
            $timezones[$value] = $value;
        }
        $this->templates = (new AdminTemplate)->getListTemplateActive();
        $this->currencies = FegguCurrency::getCodeActive();
        $this->languages = FegguLanguage::getListActive();
        $this->timezones = $timezones;
    }

    public function setting()
    {
        return Inertia::render('Partner/Setting/Index',[
            'title'=> __('Setting')
        ]);
    }

    public function room()
    {
        return Inertia::render('Partner/Rooms/Index',[
            'title'=> __('Manager Rooms'),
            'rooms'=> HospitalRoom::filter(Request::only('search','identifier'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($room) => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'status' => $room->status,
                    'price' => $room->price,
                    'level' => $room->level,
                    'class' => $room->class,
                    'clim' => $room->clim,
                    'wc_in' => $room->wc_in,
                    'bed_accompanying' => $room->bed_accompanying,
                    'room_number' => $room->room_number,
                    'total_beds'=>$room->beds->count()                ]),
            'filters' => \request()->all('search','identifier'),
        ]);
    }

    public function roomCreate()
    {
        return Inertia::render('Partner/Rooms/Create',[
            'classRoom'=> getClassroom(),
            'typeBed'=>getTypeBed(),
            'title'=> __('New Room')
        ]);
    }

    public function roomStore()
    {
        $data = Request::all();
         //dd($data);
        $dataOriginal = request()->all();
        Request::validate([
            'room_number'=>'required|numeric|min:1|unique:'.HospitalRoom::class.',id',
            'price'=>'required|numeric|min:1',
            'level'=>'required|string|min:2|max:100',
            'classRoom'=>'required|string',
            'clim'=>'nullable|boolean',
            'wc_in'=>'nullable|boolean',
            'bed_accompanying'=>'nullable|boolean',
            'beds'  => 'sometimes|required|array',
            'beds.bed_number.*'  => 'required_with:bed',
            //  'bed.status.*' => 'required_with:bed',
            'beds.type_bed.*' => 'required_with:bed',
        ],[]);

        //Check if the sku is already taken
        $data = au_clean($data,[]);
        $bed_rooms = $data['beds']??[];
        $dataInsert = [
            'hospital_id'=>\Partner::user()->partner->id,
            'room_number'=>$data['room_number'],
            'price'=>(int)$data['price'],
            'status'=>!empty($data['statusRoom'])?1:0,
            'level'=>$data['level'],
            'class'=>$data['classRoom'],
            'name'=>$data['name'],
            'clim'=>!empty($data['clim'])?1:0,
            'wc_in'=>!empty($data['wc_in'])?1:0,
            'bed_accompanying'=>!empty($data['bed_accompanying'])?1:0,
        ];
        $insertBed=[];
        if ($bed_rooms) {
            foreach ($bed_rooms as $group => $bed) {
                if (count($bed)) {
                    foreach ($bed['bed_number'] as $k => $item) {
                        $array_bed = au_clean([
                            'bed_number' => $item,
                            'status' => !empty($bed['status'][$k]) ? 1 : 0,
                            'type' => $bed['type_bed'][$k]
                        ], [], true);
                        $insertBed[] = new HospitalRoomBet($array_bed);
                    }
                }
            }
        }
        try {
            $room = HospitalRoom::create($dataInsert);
            $room->beds()->saveMany($insertBed);
        }catch (Throwable $e){
           report($e);
        }

        return redirect()->route('room.index');
    }

    public function roomEdit($id)
    {
        $room = HospitalRoom::find($id);
        if (!$room){
            abort(404);
        }
        return Inertia::render('Partner/Rooms/Edit',[
            'title'=> __('Edit Room'),
            'classRoom'=> getClassroom(),
            'typeBed'=>getTypeBed(),
            'room'=>[
                'id'=>$room->id,
                'room_number'=>$room->room_number,
                'name'=>$room->name,
                'price'=>$room->price,
                'level'=>$room->level,
                'status'=>$room->status,
                'class'=>$room->class,
                'clim'=>$room->clim,
                'wc_in'=>$room->wc_in,
                'bed_accompanying'=>$room->bed_accompanying,

            ],
            'bedsRoom'=>$room->beds()->get()->map->only('bed_number','type','status')
        ]);
    }

    public function roomUpdate($id)
    {
        $room = HospitalRoom::find($id);
      //  dd($room);
        $data = request()->all();
        // dd($id);

        $dataOriginal = request()->all();
        Request::validate([
            'room_number'=>'required|numeric|min:1|unique:'.HospitalRoom::class.',id,'.$id,
            'price'=>'required|numeric|min:1',
            'level'=>'required|string|min:2|max:100',
            'classRoom'=>'required|string',
            'clim'=>'required|boolean',
            'wc_in'=>'required|boolean',
            'bed_accompanying'=>'required|boolean',
            'bed'         => 'sometimes|required|array',
            'bed.bed_number.*'  => 'required_with:bed',
            //  'bed.status.*' => 'required_with:bed',
            'bed.type.*' => 'required_with:bed',
        ],[]);


        $dataUpdate = [
            'hospital_id'=>\Partner::user()->partner_id,
            'room_number'=>$data['room_number'],
            'price'=>(int)$data['price'],
            'status'=>!empty($data['statusRoom'])?1:0,
            'level'=>$data['level'],
            'class'=>$data['classRoom'],
            'name'=>$data['name'],
            'clim'=>!empty($data['clim'])?1:0,
            'wc_in'=>!empty($data['wc_in'])?1:0,
            'bed_accompanying'=>!empty($data['bed_accompanying'])?1:0,
        ];
    //  dd($data['beds']);
        $dataUpdate = au_clean($dataUpdate,[],true);
        $bed_rooms = $data['beds']??[];
        $room->update($dataUpdate);
        $room->beds()->delete();
        $insertBed=[];
        if ($bed_rooms) {
            foreach ($bed_rooms as $group => $bed) {
                if (count($bed)) {

                        $array_bed = au_clean([
                            'bed_number' => $bed['bed_number'],
                            'status' => !empty($bed['status']) ? 1 : 0,
                            'type' => $bed['type']
                        ], [], true);
                        $insertBed[] = new HospitalRoomBet($array_bed);
                }
            }
        }
       // dd($insertBed);
        $room->beds()->saveMany($insertBed);
        $message = __('Room and Beds updated success');
        $notify[] = ['success', $message];
        return redirect()->route('room.index')->withNotify($notify);
    }

    public function addBed()
    {
        $data = request()->all();
        // dd($data);
        $validator = Validator::make($data , [
            'type'=>'required',
            'room_id'=>'required',
            'bed_number'=>'required',
        ],[

        ]);

        if ($validator->fails()){
            return response()->json(['status'=>'error','message'=>$validator->errors()]);
        }
        $data=au_clean($data,[]);
        $dataBed = [
            'bed_number'=> $data['bed_number'],
            'type'=>$data['type'],
            'status'=>!empty($data['type'])?1:0,
            'room_id'=>$data['room_id']
        ];

        HospitalRoomBet::create($dataBed);
        $message = __('Bed added success');
        $reload=true;
        return  response()->json(['status'=>'success','message'=>$message, 'reload'=>$reload],200);
    }

    public function roomDestroy()
    {
        $idR = Request::only('id');
        $room = HospitalRoom::where('id',$idR)->first();

        if (!$room){
            abort(404);
        }
        $room->delete();
        $notify[] = ['success', 'Room Deleted Successfully with beds'];
        return redirect()->back()->withNotify($notify);
    }



    /*
     Update value config store
     */
    public function update()
    {
        $data = request()->all();
        $name = $data['name'];
        $value = $data['value'];
        $partnerId = $data['partnerId'] ?? '';
        if (!$partnerId) {
            return response()->json(
                [
                    'error' => 1,
                    'field' => 'partnerId',
                    'value' => $partnerId,
                    'msg'   => 'Store ID can not empty!',
                ]
            );
        }

        try {
            AdminConfig::where('key', $name)
                ->where('partner_id', $partnerId)
                ->update(['value' => $value]);
            $error = 0;
            $msg = au_language_render('action.update_success');
        } catch (\Throwable $e) {
            $error = 1;
            $msg = $e->getMessage();
        }
        return response()->json(
            [
                'error' => $error,
                'field' => $name,
                'value' => $value,
                'msg'   => $msg,
            ]
        );
    }

    public function show($code)
    {
        $store_id = session('partnerStorId')??'';
        $store = FegguPartner::findOrFail($store_id);
        if (!$store){
            return __('No Data Found');
        }
        return view($this->templatePathPartner.'screen.setting.show',[
            'title'=>__('Settings Caompany'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'store'=>$store,
        ]);
    }

    /**
     * Update config global
     *
     * @return  [type]  [return description]
     */
    public function updateGlobal()
    {
        $id = session('partnerId')??'';
        //dd('ok');
        // $store_id = session('partnerId')??'';
        $store = FegguPartner::findOrFail($id);
        if (!$store){
            abort(404);
        }

        $data = [
            'title' => __('Default Config'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];
        $data['store']=$store;
        return view($this->templatePathPartner.'screen.setting.config_global',$data);
    }
}
