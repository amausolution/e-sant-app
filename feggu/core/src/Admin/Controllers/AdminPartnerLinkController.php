<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminLink;
use Validator;

class AdminPartnerLinkController extends RootAdminController
{
    protected $arrTarget;

    public function __construct()
    {
        parent::__construct();
        $this->arrTarget = ['_blank' => '_blank', '_self' => '_self'];
    }

    public function arrGroup()
    {
        return   [
            'menu' => au_language_render('admin.link_position.menu'),
            'menu_left' => au_language_render('admin.link_position.menu_left'),
            'menu_right' => au_language_render('admin.link_position.menu_right'),
            'footer' => au_language_render('admin.link_position.footer'),
            'footer_right' => au_language_render('admin.link_position.footer_right'),
            'footer_left' => au_language_render('admin.link_position.footer_left'),
            'sidebar' => au_language_render('admin.link_position.sidebar'),
        ];
    }
    public function index()
    {
        $data = [
            'title' => au_language_render('admin.link.list'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_partner_link.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 1, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
        ];
        //Process add content
        $data['menuRight'] = au_config_group('menuRight', \Request::route()->getName());
        $data['menuLeft'] = au_config_group('menuLeft', \Request::route()->getName());
        $data['topMenuRight'] = au_config_group('topMenuRight', \Request::route()->getName());
        $data['topMenuLeft'] = au_config_group('topMenuLeft', \Request::route()->getName());
        $data['blockBottom'] = au_config_group('blockBottom', \Request::route()->getName());

        $listTh = [
            'name' => au_language_render('admin.link.name'),
            'url' => au_language_render('admin.link.url'),
            'target' => au_language_render('admin.link.target'),
            'group' => au_language_render('admin.link.group'),
            'sort' => au_language_render('admin.link.sort'),
            'status' => au_language_render('admin.link.status'),
        ];

        if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
            // Only show store info if store is root
            $listTh['shop_store'] = au_language_render('partner.store_list');
        }
        $listTh['action'] = au_language_render('action.title');

        $dataTmp = AdminLink::getLinkListAdmin();

        if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
            $arrId = $dataTmp->pluck('id')->toArray();
            // Only show store info if store is root

            if (function_exists('sc_get_list_store_of_link')) {
                $dataStores = sc_get_list_store_of_link($arrId);
            } else {
                $dataStores = [];
            }
        }

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataMap = [
                'name' => au_language_render($row['name']),
                'url' => $row['url'],
                'target' => $this->arrTarget[$row['target']] ?? '',
                'group' => $this->arrGroup()[$row['group']] ?? '',
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            ];

            if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
                // Only show store info if store is root
                if (!empty($dataStores[$row['id']])) {
                    $storeTmp = $dataStores[$row['id']]->pluck('code', 'id')->toArray();
                    $storeTmp = array_map(function ($code) {
                        return '<a target=_new href="'.sc_get_domain_from_code($code).'">'.$code.'</a>';
                    }, $storeTmp);
                    $dataMap['shop_store'] = '<i class="nav-icon fab fa-shopify"></i> '.implode('<br><i class="nav-icon fab fa-shopify"></i> ', $storeTmp);
                } else {
                    $dataMap['shop_store'] = '';
                }
            }
            $dataMap['action'] = '<a href="' . au_route_partner('admin_partner_link.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
            <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
            ';
            $dataTr[] = $dataMap;
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menuRight
        $data['menuRight'][] = '<a href="' . au_route_partner('admin_partner_link.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus" title="' . au_language_render('admin.link.add_new') . '"></i>
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
        $data = [
            'title'             => au_language_render('admin.link.add_new_title'),
            'subTitle'          => '',
            'title_description' => au_language_render('admin.link.add_new_des'),
            'icon'              => 'fa fa-plus',
            'link'              => [],
            'arrTarget'         => $this->arrTarget,
            'arrGroup'          => $this->arrGroup(),
            'url_action'        => au_route_partner('admin_partner_link.create'),
        ];
        return view($this->templatePathAdmin.'screen.store_link')
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
            'name'   => 'required',
            'url'    => 'required',
            'group'  => 'required',
            'target' => 'required',
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $dataInsert = [
            'name'     => $data['name'],
            'url'      => $data['url'],
            'target'   => $data['target'],
            'group'    => $data['group'],
            'sort'     => $data['sort'],
            'status'   => empty($data['status']) ? 0 : 1,
        ];
        $link = AdminLink::createLinkAdmin($dataInsert);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $link->stores()->detach();
            if ($shopStore) {
                $link->stores()->attach($shopStore);
            }
        }

        return redirect()->route('admin_partner_link.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $link = AdminLink::getLinkAdmin($id);
        if (!$link) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = [
            'title' => au_language_render('action.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'link' => $link,
            'arrTarget' => $this->arrTarget,
            'arrGroup' => $this->arrGroup(),
            'url_action' => au_route_partner('admin_partner_link.edit', ['id' => $link['id']]),
        ];
        return view($this->templatePathAdmin.'screen.store_link')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $link = AdminLink::getLinkAdmin($id);
        if (!$link) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name'   => 'required',
            'url'    => 'required',
            'group'  => 'required',
            'target' => 'required',
        ]);

        if ($validator->fails()) {
            // dd($validator->messages());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        $dataUpdate = [
            'name'     => $data['name'],
            'url'      => $data['url'],
            'target'   => $data['target'],
            'group'    => $data['group'],
            'sort'     => $data['sort'],
            'status'   => empty($data['status']) ? 0 : 1,
        ];
        $link->update($dataUpdate);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $link->stores()->detach();
            if ($shopStore) {
                $link->stores()->attach($shopStore);
            }
        }

        return redirect()->route('admin_partner_link.index')->with('success', au_language_render('action.edit_success'));
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
            AdminLink::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id)
    {
        return AdminLink::getLinkAdmin($id);
    }
}
