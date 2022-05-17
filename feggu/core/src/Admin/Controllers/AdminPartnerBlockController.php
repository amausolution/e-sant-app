<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminPartnerBlockContent;
use Feggu\Core\Admin\Models\AdminPartner;
use Feggu\Core\Partner\Models\FegguLayoutPage;
use Feggu\Core\Partner\Models\FegguLayoutPosition;
use Validator;

class AdminPartnerBlockController extends RootAdminController
{
    public $layoutType;
    public $layoutPage;
    public $layoutPosition;
    public function __construct()
    {
        parent::__construct();
        $this->layoutPage = FegguLayoutPage::getPages();
        $this->layoutType = ['html'=>'Html', 'view' => 'View'];
        $this->layoutPosition = FegguLayoutPosition::getPositions();
    }

    public function index()
    {
        $data = [
            'title'         => au_language_render('admin.store_block.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_partner_block.delete'),
            'removeList'    => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort'    => 0, // 1 - Enable button sort
            'css'           => '',
            'js'            => '',
        ];
        //Process add content
        $data['menuRight'] = au_config_group('menuRight', \Request::route()->getName());
        $data['menuLeft'] = au_config_group('menuLeft', \Request::route()->getName());
        $data['topMenuRight'] = au_config_group('topMenuRight', \Request::route()->getName());
        $data['topMenuLeft'] = au_config_group('topMenuLeft', \Request::route()->getName());
        $data['blockBottom'] = au_config_group('blockBottom', \Request::route()->getName());

        $listTh = [
            'id'       => 'ID',
            'name'     => au_language_render('admin.store_block.name'),
            'type'     => au_language_render('admin.store_block.type'),
            'position' => au_language_render('admin.store_block.position'),
            'page'     => au_language_render('admin.store_block.page'),
            'text'     => au_language_render('admin.store_block.text'),
            'sort'     => au_language_render('admin.store_block.sort'),
            'status'   => au_language_render('admin.store_block.status'),
            'template'   => 'Template',
        ];
        if ((au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) && session('adminPartnerId') == AU_ID_ROOT) {
            // Only show store info if store is root
            $listTh['shop_store'] = au_language_render('partner.store_list');
        }
        $listTh['action'] = au_language_render('action.title');

        $dataTmp = (new AdminPartnerBlockContent)->getStoreBlockContentListAdmin();

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $layoutPage = $this->layoutPage;
            $htmlPage = '';
            if (!$row['page']) {
                $htmlPage .= '';
            } elseif (strpos($row['page'], '*') !== false) {
                $htmlPage .= au_language_render('admin.layout_page_position.all');
            } else {
                $arrPage = explode(',', $row['page']);
                foreach ($arrPage as $key => $value) {
                    $htmlPage .= '+' . $value . '<br>';
                }
            }

            $type_name = $this->layoutType[$row['type']] ?? '';
            if ($row['type'] == 'view') {
                $type_name = '<span class="badge badge-warning">' . $type_name . '</span>';
            } elseif ($row['type'] == 'html') {
                $type_name = '<span class="badge badge-primary">' . $type_name . '</span>';
            }

            $storeTmp = [
                'id' => $row['id'],
                'name' => $row['name'],
                'type' => $type_name,
                'position' => htmlspecialchars(au_language_render($this->layoutPosition[$row['position']]) ?? ''),
                'page' => $htmlPage,
                'text' => htmlspecialchars($row['text']),
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'template' => $row['template'],
            ];

            if ((au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) && session('adminPartnerId') == AU_ID_ROOT) {
                $storeCode = au_get_list_code_partner()[$row['partner_id']] ?? '';
                // Only show store info if store is root
                $storeTmp['shop_store'] = '<i class="nav-icon fab fa-shopify"></i><a target=_new href="'.sc_get_domain_from_code($storeCode).'">'.$storeCode.'</a>';
            }

            $storeTmp['action'] = '
                <a href="' . au_route_partner('admin_partner_block.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
            <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
            ';
            $dataTr[] = $storeTmp;

        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menuRight
        $data['menuRight'][] = '
                           <a href="' . au_route_partner('admin_partner_block.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus" title="'.au_language_render('action.add').'"></i>
                           </a>';
        //=menuRight

        return view($this->templatePathAdmin.'screen.list')
            ->with($data);
    }

    /**
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $listViewBlock = $this->getListViewBlock();
        $data = [
            'title'             => au_language_render('admin.store_block.add_new_title'),
            'subTitle'          => '',
            'title_description' => au_language_render('admin.store_block.add_new_des'),
            'icon'              => 'fa fa-plus',
            'layoutPosition'    => $this->layoutPosition,
            'layoutPage'        => $this->layoutPage,
            'layoutType'        => $this->layoutType,
            'listViewBlock'     => $listViewBlock,
            'layout'            => [],
            'url_action'        => au_route_partner('admin_partner_block.create'),
        ];
        return view($this->templatePathAdmin.'screen.store_block')
            ->with($data);
    }

    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $partnerId = $data['partner_id'] ?? session('adminPartnerId');
        $store = AdminPartner::find($partnerId);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required',
            'page' => 'required',
            'position' => 'required',
            'text' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataInsert = [
            'name'     => $data['name'],
            'position' => $data['position'],
            'page'     => in_array('*', $data['page'] ?? []) ? '*' : implode(',', $data['page'] ?? []),
            'text'     => $data['text'],
            'type'     => $data['type'],
            'sort'     => (int) $data['sort'],
            'template' => $store->template,
            'status'   => (empty($data['status']) ? 0 : 1),
            'partner_id' => $partnerId,
        ];
        AdminPartnerBlockContent::createStoreBlockContentAdmin($dataInsert);
        //
        return redirect()->route('admin_partner_block.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $layout = (new AdminPartnerBlockContent)->getStoreBlockContentAdmin($id);
        if (!$layout) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $listViewBlock = $this->getListViewBlock($layout->partner_id);

        $data = [
            'title' => au_language_render('action.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'layoutPosition' => $this->layoutPosition,
            'layoutPage' => $this->layoutPage,
            'layoutType' => $this->layoutType,
            'listViewBlock' => $listViewBlock,
            'layout' => $layout,
            'partnerId' => $layout->partner_id,
            'url_action' => au_route_partner('admin_partner_block.edit', ['id' => $layout['id']]),
        ];
        return view($this->templatePathAdmin.'screen.store_block')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $partnerId = $data['partner_id'] ?? session('adminPartnerId');
        $store = AdminPartner::find($partnerId);

        $layout = (new AdminPartnerBlockContent)->getStoreBlockContentAdmin($id);
        if (!$layout) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
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
        $dataUpdate = [
            'name' => $data['name'],
            'position' => $data['position'],
            'page' => in_array('*', $data['page'] ?? []) ? '*' : implode(',', $data['page'] ?? []),
            'text' => $data['text'],
            'type' => $data['type'],
            'sort' => (int) $data['sort'],
            'template' => $store->template,
            'status' => (empty($data['status']) ? 0 : 1),
            'partner_id' => $partnerId,
        ];
        $layout->update($dataUpdate);
        //
        return redirect()->route('admin_partner_block.index')->with('success', au_language_render('action.edit_success'));
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
            $arrDontPermission = [];
            foreach ($arrID as $key => $id) {
                if (!$this->checkPermisisonItem($id)) {
                    $arrDontPermission[] = $id;
                }
            }
            if (count($arrDontPermission)) {
                return response()->json(['error' => 1, 'msg' => au_language_render('admin.remove_dont_permisison') . ': ' . json_encode($arrDontPermission)]);
            }
            AdminPartnerBlockContent::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Get view block
     *
     * @return  [type]  [return description]
     */
    public function getListViewBlock($partnerId = null)
    {
        $arrView = [];
        foreach (glob(base_path() . "/resources/views/templates/".au_partner('template', $partnerId)."/block/*.blade.php") as $file) {
            if (file_exists($file)) {
                $arr = explode('/', $file);
                $arrView[substr(end($arr), 0, -10)] = substr(end($arr), 0, -10);
            }
        }
        return $arrView;
    }


    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id)
    {
        return (new AdminPartnerBlockContent)->getStoreBlockContentAdmin($id);
    }

    /**
     * Get json list view block
     *
     * @return void
     */
    public function getListViewBlockHtml() {
        if (!request()->ajax()) {
            $html =  '';
        } else {
            $html = '<select name="text" class="form-control text">';
            $partnerId = request('partner_id');
            $arrView = [];
            foreach (glob(base_path() . "/resources/views/templates/".au_partner('template', $partnerId)."/block/*.blade.php") as $file) {
                if (file_exists($file)) {
                    $arr = explode('/', $file);
                    $arrView[substr(end($arr), 0, -10)] = substr(end($arr), 0, -10);
                    $html .='<option value="'.substr(end($arr), 0, -10).'">'.substr(end($arr), 0, -10);
                    $html .='</option>';
                }
            }
            $html .='</select>';
            $html .='<span class="form-text"><i class="fa fa-info-circle"></i>';
            $html .= au_language_render('admin.store_block.helper_view', ['template' => au_partner('template', $partnerId)]);
            $html .='</span>';
        }
        return $html;
    }
}
