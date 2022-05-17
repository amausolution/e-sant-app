<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\FegguCustomField;
use Validator;

class AdminCustomFieldController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => au_language_render('admin.custom_field.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . au_language_render('admin.custom_field.add_new_title'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_custom_field.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
            'url_action' => au_route_partner('admin_custom_field.create'),
        ];

        $listTh = [
            'id' => 'ID',
            'type' => au_language_render('admin.custom_field.type'),
            'code' => au_language_render('admin.custom_field.code'),
            'name' => au_language_render('admin.custom_field.name'),
            'required' => au_language_render('admin.custom_field.required'),
            'option' => au_language_render('admin.custom_field.option'),
            'default' => au_language_render('admin.custom_field.default'),
            'status' => au_language_render('admin.custom_field.status'),
            'action' => au_language_render('action.title'),
        ];
        $obj = new FegguCustomField;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[] = [
                'id' => $row['id'],
                'type' => $row['type'],
                'code' => $row['code'],
                'name' => $row['name'],
                'required' => $row['required'],
                'option' => $row['option'],
                'default' => $row['default'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => '
                    <a href="' . au_route_partner('admin_custom_field.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->templatePathAdmin.'screen.custom_field')
            ->with($data);
    }


    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();

        $validator = Validator::make($data, [
            'type' => 'required|string|max:100',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $dataInsert = [
            'type' => $data['type'],
            'name' => $data['name'],
            'code' => $data['code'],
            'option' => $data['option'],
            'default' => $data['default'],
            'required' => (!empty($data['required']) ? 1 : 0),
            'status' => (!empty($data['status']) ? 1 : 0),
        ];
        $obj = FegguCustomField::create($dataInsert);

        return redirect()->route('admin_custom_field.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $customField = FegguCustomField::find($id);
        if (!$customField) {
            return 'No data';
        }
        $data = [
            'title' => au_language_render('admin.custom_field.list'),
            'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . au_language_render('action.edit'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_custom_field.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
            'url_action' => au_route_partner('admin_custom_field.edit', ['id' => $customField['id']]),
            'customField' => $customField,
            'id' => $id,
        ];

        $listTh = [
            'id' => 'ID',
            'type' => au_language_render('admin.custom_field.type'),
            'code' => au_language_render('admin.custom_field.code'),
            'name' => au_language_render('admin.custom_field.name'),
            'required' => au_language_render('admin.custom_field.required'),
            'option' => au_language_render('admin.custom_field.option'),
            'default' => au_language_render('admin.custom_field.default'),
            'status' => au_language_render('admin.custom_field.status'),
            'action' => au_language_render('action.title'),
        ];
        $obj = new FegguCustomField;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataTr[] = [
                'id' => $row['id'],
                'type' => $row['type'],
                'code' => $row['code'],
                'name' => $row['name'],
                'required' => $row['required'],
                'option' => $row['option'],
                'default' => $row['default'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => '
                    <a href="' . au_route_partner('admin_custom_field.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->templatePathAdmin.'screen.custom_field')
            ->with($data);
    }


    /**
     * update status
     */
    public function postEdit($id)
    {
        $customField = FegguCustomField::find($id);
        $data = request()->all();
        $validator = Validator::make($data, [
            'type' => 'required|string|max:100',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        //Edit

        $dataUpdate = [
            'type' => $data['type'],
            'name' => $data['name'],
            'code' => $data['code'],
            'option' => $data['option'],
            'default' => $data['default'],
            'required' => (!empty($data['required']) ? 1 : 0),
            'status' => (!empty($data['status']) ? 1 : 0),
        ];

        $customField->update($dataUpdate);

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
            FegguCustomField::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
