<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\HospitalRoom;
use Feggu\Core\Partner\Models\HospitalRoomBet;
use Illuminate\Http\Request;
use Validator;

class RoomsController extends RootPartnerController
{
    /**
     * RoomsController constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        return view($this->templatePathPartner.'rooms.index',[
            'title'=> __('Listing Rooms'),
            'subTitle' => __('Rooms'),
            'js'=>'',
            'css'=>''
        ]);
    }

    public function create()
    {
       return view($this->templatePathPartner. 'rooms.create',[
           'title'=> __('Create new room'),
           'subTitle'=> __('New Room'),
           'action_url'=>au_route_partner('room.store')
       ]);
    }

    public function store()
    {
        $data = request()->all();
       // dd($id);
        $dataOriginal = request()->all();
        $validator = Validator::make($dataOriginal , [
            'hospital_id'=>'required',
            'room_number'=>'required|numeric|min:1|unique:'.AU_DB_PREFIX.'hospital_room,id',
            'price'=>'required|numeric|min:1',
            'level'=>'required|string|min:2|max:100',
            'class'=>'required|string',
            'clim'=>'required|boolean',
            'wc_in'=>'required|boolean',
            'bed_accompanying'=>'required|boolean',
            'bed'         => 'sometimes|required|array',
            'bed.bed_number.*'  => 'required_with:bed',
          //  'bed.status.*' => 'required_with:bed',
            'bed.type.*' => 'required_with:bed',
        ],[]);
        if ($validator->fails()){
            return response()->json(['status'=>'error','message' => $validator->errors()]);
        }
        //Check if the sku is already taken
        $data = au_clean($data,[]);
        $bed_rooms = $data['bed']??[];
        $dataInsert = [
            'hospital_id'=>$data['hospital_id'],
            'room_number'=>$data['room_number'],
            'price'=>(int)$data['price'],
            'status'=>!empty($data['status'])?1:0,
            'level'=>$data['level'],
            'class'=>$data['class'],
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
                            'type' => $bed['type'][$k]
                        ], [], true);
                        $insertBed[] = new HospitalRoomBet($array_bed);
                    }
                }
            }
        }

            $room = HospitalRoom::create($dataInsert);
            $message = __('Room and Beds saved success');
            $reload = false;

        $room->beds()->saveMany($insertBed);
        return response()->json(['status'=> 'success', 'message'=>$message, 'reload'=>$reload],200);
    }

    public function edit($id)
    {
        $room = HospitalRoom::find($id);
        if (!$room){
            abort(404);
        }
        return view($this->templatePathPartner. 'rooms.create',[
            'title'=> __('Create new room'),
            'subTitle'=> __('New Room'),
            'room'=>$room,
            'action_url'=>au_route_partner('room.update',['id'=>$id])
        ]);
    }

    public function update($id)
    {
        $data = request()->all();
        // dd($id);
        $dataOriginal = request()->all();
      \Illuminate\Support\Facades\Request::validate([
            'hospital_id'=>'required',
            'room_number'=>'required|numeric|min:1|unique:'.AU_DB_PREFIX.'hospital_room,id,'.$id,
            'price'=>'required|numeric|min:1',
            'level'=>'required|string|min:2|max:100',
            'class'=>'required|string',
            'clim'=>'required|boolean',
            'wc_in'=>'required|boolean',
            'bed_accompanying'=>'required|boolean',
            'bed'         => 'sometimes|required|array',
            'bed.bed_number.*'  => 'required_with:bed',
            //  'bed.status.*' => 'required_with:bed',
            'bed.type.*' => 'required_with:bed',
        ],[]);




        $bed_rooms = $data['bed']??[];
        $dataInsert = [
            'hospital_id'=>$data['hospital_id'],
            'room_number'=>$data['room_number'],
            'price'=>(int)$data['price'],
            'status'=>!empty($data['status'])?1:0,
            'level'=>$data['level'],
            'class'=>$data['class'],
            'name'=>$data['name'],
            'clim'=>!empty($data['clim'])?1:0,
            'wc_in'=>!empty($data['wc_in'])?1:0,
            'bed_accompanying'=>!empty($data['bed_accompanying'])?1:0,
        ];
        $dataInsert = au_clean($dataInsert,[],true);
        $room = HospitalRoom::find($id);
        $room->update($dataInsert);
        $room->beds()->delete();
        $insertBed=[];
        if ($bed_rooms) {
            foreach ($bed_rooms as $group => $bed) {
                if (count($bed)) {
                    foreach ($bed['bed_number'] as $k => $item) {
                        $array_bed = au_clean([
                            'bed_number' => $item,
                            'status' => !empty($bed['status'][$k]) ? 1 : 0,
                            'type' => $bed['type'][$k]
                        ], [], true);
                        $insertBed[] = new HospitalRoomBet($array_bed);
                    }
                }
            }
        }
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

    public function destroy($id)
    {
      $room = HospitalRoom::find($id);

      if (!$room){
          abort(404);
      }
        $room->delete();
        $notify[] = ['success', 'Room Deleted Successfully with beds'];
        return redirect()->back()->withNotify($notify);
    }
}
