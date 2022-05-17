<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\FegguBannerType;
use Validator;

class AdminBannerTypeController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $data = [
            'title' => au_language_render('admin.banner_type.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . au_language_render('admin.banner_type.add_new_title'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_banner_type.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
            'url_action' => au_route_partner('admin_banner_type.create'),
        ];

        $listTh = [
            'id' => 'ID',
            'code' => au_language_render('admin.banner_type.code'),
            'name' => au_language_render('admin.banner_type.name'),
            'action' => au_language_render('action.title'),
        ];
        $obj = new FegguBannerType;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[] = [
                'id' => $row['id'],
                'code' => $row['code'] ?? 'N/A',
                'name' => $row['name'] ?? 'N/A',
                'action' => '
                    <a href="' . au_route_partner('admin_banner_type.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->templatePathAdmin.'screen.banner_type')
            ->with($data);
    }

    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required',
            'code' => 'required|unique:"'.FegguBannerType::class.'",code',
        ], [
            'name.required' => au_language_render('validation.required'),
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data['code'] = au_word_format_url($data['code']);
        $data['code'] = au_word_limit($data['code'], 100);
        $dataInsert = [
            'code' => $data['code'],
            'name' => $data['name'],
        ];
        $obj = FegguBannerType::create($dataInsert);
//
        return redirect()->route('admin_banner_type.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $banner_type = FegguBannerType::find($id);
        if (!$banner_type) {
            return 'No data';
        }
        $data = [
        'title' => au_language_render('admin.banner_type.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . au_language_render('action.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-indent',
        'urlDeleteItem' => au_route_partner('admin_banner_type.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'buttonSort' => 0, // 1 - Enable button sort
        'css' => '',
        'js' => '',
        'url_action' => au_route_partner('admin_banner_type.edit', ['id' => $banner_type['id']]),
        'banner_type' => $banner_type,
        'id' => $id,
    ];

        $listTh = [
        'id' => 'ID',
        'code' => au_language_render('admin.banner_type.code'),
        'name' => au_language_render('admin.banner_type.name'),
        'action' => au_language_render('action.title'),
    ];
        $obj = new FegguBannerType;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[] = [
            'id' => $row['id'],
            'code' => $row['code'] ?? 'N/A',
            'name' => $row['name'] ?? 'N/A',
            'action' => '
                <a href="' . au_route_partner('admin_banner_type.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

              <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
              ',
        ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->templatePathAdmin.'screen.banner_type')
        ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $obj = FegguBannerType::find($id);
        $validator = Validator::make($dataOrigin, [
            'code' => 'required|unique:"'.FegguBannerType::class.'",code,' . $obj->id . ',id',
            'name' => 'required',
        ], [
            'name.required' => au_language_render('validation.required'),
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        $data['code'] = au_word_format_url($data['code']);
        $data['code'] = au_word_limit($data['code'], 100);
        $dataUpdate = [
            'code' => $data['code'],
            'name' => $data['name'],
        ];
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
            return response()->json(['error' => 1, 'msg' => au_language_render('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            FegguBannerType::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
