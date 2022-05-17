<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use App\Partner\Models\TimetableDoctor;
use Feggu\Core\Partner\Models\PartnerUser;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TimeTableController extends RootPartnerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    return view($this->templatePathPartner.'timetable.index',[
        'title'=> __('Listing Timetable '),
        'subTitle'=> __('All Timetables For Current Month And Year'),
        'css'=>'',
        'js'=>''
    ]);
    }

    public function create()
    {
        return view($this->templatePathPartner.'timetable.create',[
            'title' => __('New Timetable'),
            'subTitle'=> __('Add new timetable'),
            'action_url'=>au_route_partner('timetable.store')
        ]);
    }

    public function store()
    {
        $data = request()->all();
        $user = PartnerUser::find($data['pu']);

        $dataInsert = array(date('Y-m').'-'.explode(' ',$data['day'])[1]=>
             array(
                'month_id'=>date('m'),
                'day_letter'=> explode(' ',$data['day'])[0],
                'day_number'=>explode(' ',$data['day'])[1],
                'department_id'=>$data['department'],
                'time_start'=>$data['time_start'],
                'time_break'=>$data['time_break'],
                'time_end'=>$data['time_end'],
                'duration_break'=>$data['duration_break']
            )
        );

        if (!empty($user->timetable)){
            $b=$user->timetable;
            $b[] = $dataInsert;
           // dd($b);
            dd(Arr::flatten($b));
            $user->update(['timetable'=>$new]);
        }else{
            $user->update(['timetable'=>$dataInsert]);
        }
    }

    public function edit($id)
    {

    }

    public function update($id)
    {

    }

    public function delete()
    {

    }
}
