<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\AdminMenuPartner;
use Validator;

class AdminMenuPartnerController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title' => au_language_render('admin.menu_partner.list'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'menu' => [],
            'treeMenu' => (new AdminMenuPartner())->getTree(),
            'url_action' => au_route_partner('admin_menu_partner.create'),
            'urlDeleteItem' => au_route_partner('admin_menu_partner.delete'),
            'title_form' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . au_language_render('admin.menu_partner.create'),
        ];
        $data['layout'] = 'index';
        return view($this->templatePathAdmin.'screen.list_menu_partner')
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
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataInsert = [
            'title' => $data['title'],
            'parent_id' => $data['parent_id'],
            'uri' => $data['uri'],
            'icon' => $data['icon'],
            'sort' => $data['sort'] ?? 0,
        ];

        AdminMenuPartner::createMenu($dataInsert);
        return redirect()->route('admin_menu_partner.index')->with('success', au_language_render('admin.menu.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $menu = AdminMenuPartner::find($id);
        if ($menu === null) {
            return 'no data';
        }
        $data = [
            'title' => au_language_render('admin.menu_partner.list'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'menu' => $menu,
            'treeMenu' => (new AdminMenuPartner())->getTree(),
            'url_action' => au_route_partner('admin_menu_partner.edit', ['id' => $menu['id']]),
            'title_form' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . au_language_render('admin.menu.edit'),
        ];
        $data['urlDeleteItem'] = au_route_partner('admin_menu_partner.delete');
        $data['id'] = $id;
        $data['layout'] = 'edit';
        return view($this->templatePathAdmin.'screen.list_menu_partner')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $menu = AdminMenuPartner::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'title' => 'required',
            'parent_id'=>'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'title' => $data['title'],
            'parent_id' => $data['parent_id'],
            'uri' => $data['uri'],
            'icon' => $data['icon'],
            'sort' => $data['sort'] ?? 0,
        ];

        AdminMenuPartner::updateInfo($dataUpdate, $id);
        return redirect()->back()->with('success', au_language_render('admin.menu_partner.edit_success'));
    }

    /*
    Delete list Item
    Need mothod destroy to boot deleting in model
     */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => au_language_render('admin.method_not_allow')]);
        } else {
            $id = request('id');
            $check = AdminMenuPartner::where('parent_id', $id)->count();
            if ($check) {
                return response()->json(['error' => 1, 'msg' => au_language_render('admin.menu.error_have_child')]);
            } else {
                AdminMenuPartner::destroy($id);
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
        $response = (new AdminMenuPartner)->reSort($newTree);
        return $response;
    }
}
