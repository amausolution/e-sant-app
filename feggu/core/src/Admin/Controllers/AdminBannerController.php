<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Validator;
use Feggu\Core\Admin\Models\AdminBanner;
use Feggu\Core\Partner\Models\FegguBannerType;

class AdminBannerController extends RootAdminController
{
    protected $arrTarget;
    protected $dataType;
    public function __construct()
    {
        parent::__construct();
        $this->arrTarget = ['_blank' => '_blank', '_self' => '_self'];
        $this->dataType  = (new FegguBannerType)->pluck('name', 'code')->all();
        if (au_config_global('MultiVendorPro')) {
            $this->dataType['background-store'] = 'Background store';
            $this->dataType['breadcrumb-store'] = 'Breadcrumb store';
        }
        ksort($this->dataType);
    }

    public function index()
    {
        $data = [
            'title'         => au_language_render('admin.banner.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_banner.delete'),
            'removeList'    => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort'    => 1, // 1 - Enable button sort
            'css'           => '',
            'js'            => '',
        ];
        //Process add content
        $data['menuRight']    = au_config_group('menuRight', \Request::route()->getName());
        $data['menuLeft']     = au_config_group('menuLeft', \Request::route()->getName());
        $data['topMenuRight'] = au_config_group('topMenuRight', \Request::route()->getName());
        $data['topMenuLeft']  = au_config_group('topMenuLeft', \Request::route()->getName());
        $data['blockBottom']  = au_config_group('blockBottom', \Request::route()->getName());

        $listTh = [
            'image'  => au_language_render('admin.banner.image'),
            'title'  => au_language_render('admin.banner.title'),
            'url'    => au_language_render('admin.banner.url'),
            'sort'   => au_language_render('admin.banner.sort'),
            'status' => au_language_render('admin.banner.status'),
            'click'  => au_language_render('admin.banner.click'),
            'target' => au_language_render('admin.banner.target'),
            'type'   => au_language_render('admin.banner.type'),
        ];
        if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
            // Only show store info if store is root
            $listTh['shop_store'] = au_language_render('partner.store_list');
        }
        $listTh['action'] = au_language_render('action.title');

        $sort_order = au_clean(request('sort_order') ?? 'id_desc');
        $keyword    = au_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => au_language_render('filter_sort.id_desc'),
            'id__asc' => au_language_render('filter_sort.id_asc'),
        ];

        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];
        $dataTmp = AdminBanner::getBannerListAdmin($dataSearch);

        if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
            $arrId = $dataTmp->pluck('id')->toArray();
            // Only show store info if store is root
            if (function_exists('sc_get_list_store_of_banner')) {
                $dataStores = sc_get_list_store_of_banner($arrId);
            } else {
                $dataStores = [];
            }
        }

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataMap = [
                'image' => au_image_render($row->getThumb(), '', '50px', 'Banner'),
                'title' => $row['title'],
                'url' => $row['url'],
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'click' => number_format($row['click']),
                'target' => $row['target'],
                'type' => $this->dataType[$row['type']]??'N/A',
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
            $dataMap['action'] = '<a href="' . au_route_partner('admin_banner.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
            <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
            ';
            $dataTr[] = $dataMap;
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menuRight
        $data['menuRight'][] = '<a href="' . au_route_partner('admin_banner.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
        <i class="fa fa-plus" title="'.au_language_render('action.add').'"></i>
                           </a>
                           <a href="' . au_route_partner('admin_banner_type.index') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-search-plus" aria-hidden="true"></i>
                           </a>';
        //=menuRight

        //menuSearch
        $data['topMenuRight'][] = '
        <form action="' . au_route_partner('admin_banner.index') . '" id="button_search">
        <div class="input-group input-group" style="width: 350px;">
            <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . au_language_render('search.placeholder') . '" value="' . $keyword . '">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </div>
        </div>
        </form>';
        //=menuSearch


        //menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        $data['urlSort'] = au_route_partner('admin_banner.index', request()->except(['_token', '_pjax', 'sort_order']));
        $data['optionSort'] = $optionSort;
        //=menuSort

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
            'title' => au_language_render('admin.banner.add_new'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'banner' => [],
            'arrTarget' => $this->arrTarget,
            'dataType' => $this->dataType,
            'url_action' => au_route_partner('admin_banner.create'),
        ];
        return view($this->templatePathAdmin.'screen.banner')
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
            'sort' => 'numeric|min:0',
            'email' => 'email|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $dataInsert = [
            'image'    => $data['image'],
            'url'      => $data['url'],
            'title'    => $data['title'],
            'html'     => $data['html'],
            'type'     => $data['type'] ?? 0,
            'target'   => $data['target'],
            'status'   => empty($data['status']) ? 0 : 1,
            'sort'     => (int) $data['sort'],
        ];
        $banner = AdminBanner::createBannerAdmin($dataInsert);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $banner->stores()->detach();
            if ($shopStore) {
                $banner->stores()->attach($shopStore);
            }
        }

        return redirect()->route('admin_banner.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $banner = AdminBanner::getBannerAdmin($id);

        if (!$banner) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data = [
            'title'             => au_language_render('action.edit'),
            'subTitle'          => '',
            'title_description' => '',
            'icon'              => 'fa fa-edit',
            'arrTarget'         => $this->arrTarget,
            'dataType'          => $this->dataType,
            'banner'            => $banner,
            'url_action'        => au_route_partner('admin_banner.edit', ['id' => $banner['id']]),
        ];
        return view($this->templatePathAdmin.'screen.banner')
            ->with($data);
    }

    /*
     * update status
     */
    public function postEdit($id)
    {
        $banner = AdminBanner::getBannerAdmin($id);
        if (!$banner) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'sort' => 'numeric|min:0',
            'email' => 'email|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        $dataUpdate = [
            'image'    => $data['image'],
            'url'      => $data['url'],
            'title'    => $data['title'],
            'html'     => $data['html'],
            'type'     => $data['type'] ?? 0,
            'target'   => $data['target'],
            'status'   => empty($data['status']) ? 0 : 1,
            'sort'     => (int) $data['sort'],
        ];
        $banner->update($dataUpdate);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $banner->stores()->detach();
            if ($shopStore) {
                $banner->stores()->attach($shopStore);
            }
        }

        return redirect()->route('admin_banner.index')->with('success', au_language_render('action.edit_success'));
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

            AdminBanner::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id)
    {
        return AdminBanner::getBannerAdmin($id);
    }
}
