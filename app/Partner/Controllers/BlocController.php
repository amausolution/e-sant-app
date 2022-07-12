<?php


namespace App\Partner\Controllers;


use App\Http\Controllers\RootPartnerController;
use App\Partner\Models\BlocRoom;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use function PHPUnit\Framework\directoryExists;

class BlocController extends RootPartnerController
{

    public function index()
    {
     return  Inertia::render('Partner/Bloc/Index',[
            'blocs'=> BlocRoom::all(),
            'title'=> __('List Bloc Operator')
       ]);
    }


    public function store()
    {
        $data = Request::only('status','name','number');
        Request::validate([
            'number'=>'required|numeric|min:1',
            'name'=> 'nullable|string|max:100',
        ],[]);
        //dd($data);
        $dataInsert = [
            'name'=>$data['name'],
            'number'=>$data['number'],
            'status'=>!empty($data['status'])?1:0,
            'partner_id'=>getPartner()->id
        ];

        $dataInsert= au_clean($dataInsert,[],true);

        BlocRoom::create($dataInsert);

        return redirect()->route('bloc.index');

    }


    public function update($id)
    {
        $data = Request::only('status','name','number');
        //dd($data);
        Request::validate([
           'number'=>'required|numeric|min:1',
           'name'=> 'nullable|string|max:100',
        ],[]);

        $bloc = BlocRoom::findOrFail($id);
        $dataUp = [
            'name'=>$data['name'],
            'number'=>$data['number'],
            'status'=>!empty($data['status'])?1:0
        ];

        $dataUp= au_clean($dataUp,[],true);

        $bloc->update($dataUp);
        return redirect()->route('bloc.index');
    }

    public function delete()
    {
       $data = Request::all('id');
       $bloc = BlocRoom::findOrFail($data['id']);

       $bloc->delete();
       return redirect()->route('bloc.index');
    }

}
