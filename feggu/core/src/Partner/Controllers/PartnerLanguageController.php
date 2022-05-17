<?php
namespace Feggu\Core\Partner\Controllers;

use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\FegguLanguage;
use Validator;

class PartnerLanguageController extends RootPartnerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title' => au_language_render('partner.language.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . au_language_render('partner.language.add_new_title'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('partner_language.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
            'url_action' => au_route_partner('partner_language.create'),
        ];

        $listTh = [
            'id' => 'ID',
            'name' => au_language_render('partner.language.name'),
            'code' => au_language_render('partner.language.code'),
            'icon' => au_language_render('partner.language.icon'),
            'rtl' => au_language_render('partner.language.layout_rtl'),
            'sort' => au_language_render('partner.language.sort'),
            'status' => au_language_render('partner.language.status'),
            'action' => au_language_render('action.title'),
        ];

        $obj = new FegguLanguage;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'code' => $row['code'],
                'icon' => au_image_render($row['icon'], '30px', '30px', $row['name']),
                'rtl' => $row['rtl'],
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => '
                    <a href="' . au_route_partner('partner_language.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span ' . (in_array($row['id'], AU_GUARD_LANGUAGE) ? "style='display:none'" : "") . ' onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathPartner.'component.pagination');
        $data['resultItems'] = au_language_render('partner.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->templatePathPartner.'screen.language')
            ->with($data);
    }

    /**
     * Post create
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'icon' => 'required',
            'sort' => 'numeric|min:0',
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:"'.FegguLanguage::class.'",code',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataInsert = [
            'icon' => $data['icon'],
            'name' => $data['name'],
            'code' => $data['code'],
            'rtl' => empty($data['rtl']) ? 0 : 1,
            'status' => empty($data['status']) ? 0 : 1,
            'sort' => (int) $data['sort'],
        ];
        $obj = FegguLanguage::create($dataInsert);

        return redirect()->route('partner_language.edit', ['id' => $obj['id']])->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $language = FegguLanguage::find($id);
        if (!$language) {
            return 'No data';
        }
        $data = [
        'title' => au_language_render('partner.language.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . au_language_render('action.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-indent',
        'urlDeleteItem' => au_route_partner('partner_language.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'buttonSort' => 0, // 1 - Enable button sort
        'css' => '',
        'js' => '',
        'url_action' => au_route_partner('partner_language.edit', ['id' => $language['id']]),
        'language' => $language,
    ];

        $listTh = [
        'id' => 'ID',
        'name' => au_language_render('partner.language.name'),
        'code' => au_language_render('partner.language.code'),
        'icon' => au_language_render('partner.language.icon'),
        'rtl' => au_language_render('partner.language.layout_rtl'),
        'sort' => au_language_render('partner.language.sort'),
        'status' => au_language_render('partner.language.status'),
        'action' => au_language_render('action.title'),
    ];
        $obj = new FegguLanguage;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'code' => $row['code'],
            'icon' => au_image_render($row['icon'], '30px', '30px', $row['name']),
            'rtl' => $row['rtl'],
            'sort' => $row['sort'],
            'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            'action' => '
                <a href="' . au_route_partner('partner_language.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

              <span ' . (in_array($row['id'], AU_GUARD_LANGUAGE) ? "style='display:none'" : "") . ' onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
              ',
        ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathPartner.'component.pagination');
        $data['resultItems'] = au_language_render('partner.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->templatePathPartner.'screen.language')
        ->with($data);
    }

    /**
     * update
     */
    public function postEdit($id)
    {
        $language = FegguLanguage::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'icon' => 'required',
            'name' => 'required',
            'sort' => 'numeric|min:0',
            'code' => 'required|string|max:10|unique:"'.FegguLanguage::class.'",code,' . $language->id . ',id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'icon' => $data['icon'],
            'name' => $data['name'],
            'code' => $data['code'],
            'rtl' => empty($data['rtl']) ? 0 : 1,
            'sort' => $data['sort'],
        ];
        //Check status before change
        $check = FegguLanguage::where('status', 1)->where('code', '<>', $data['code'])->count();
        if ($check) {
            $dataUpdate['status'] = empty($data['status']) ? 0 : 1;
        } else {
            $dataUpdate['status'] = 1;
        }
        //End check status
        $obj = FegguLanguage::find($id);
        $obj->update($dataUpdate);

//
        return redirect()->back()->with('success', au_language_render('action.edit_success'));
    }

    /*
        Delete list item
        Need mothod destroy to boot deleting in model
     */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => au_language_render('partner.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            $arrID = array_diff($arrID, AU_GUARD_LANGUAGE);
            FegguLanguage::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
