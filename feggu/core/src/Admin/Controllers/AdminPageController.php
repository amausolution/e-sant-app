<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Admin\Models\AdminPage;
use Validator;

class AdminPageController extends RootAdminController
{
    public $languages;

    public function __construct()
    {
        parent::__construct();
        $this->languages = FegguLanguage::getListActive();
    }

    public function index()
    {
        $data = [
            'title'         => au_language_render('admin.page.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_page.delete'),
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
            'title'  => au_language_render('admin.page.title'),
            'image'  => au_language_render('admin.page.image'),
            'alias'  => au_language_render('admin.page.alias'),
            'status' => au_language_render('admin.page.status'),
        ];
        if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
            // Only show store info if store is root
            $listTh['shop_store'] = au_language_render('partner.store_list');
        }
        $listTh['action'] = au_language_render('action.title');

        $sort_order = au_clean(request('sort_order') ?? 'id_desc');
        $keyword    = au_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc'    => au_language_render('filter_sort.id_desc'),
            'id__asc'     => au_language_render('filter_sort.id_asc'),
            'title__desc' => au_language_render('filter_sort.title_desc'),
            'title__asc'  => au_language_render('filter_sort.title_asc'),
        ];

        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];
        $dataTmp = AdminPage::getPageListAdmin($dataSearch);

        if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
            $arrId = $dataTmp->pluck('id')->toArray();
            // Only show store info if store is root
            if (function_exists('sc_get_list_store_of_page')) {
                $dataStores = sc_get_list_store_of_page($arrId);
            } else {
                $dataStores = [];
            }
        }

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataMap = [
                'title' => $row['title'],
                'image' => au_image_render($row['image'], '50px', '', $row['title']),
                'alias' => $row['alias'],
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
            $dataMap['action'] = '<a href="' . au_route_partner('admin_page.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
            <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
            ';
            $dataTr[] = $dataMap;
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);


        //menuRight
        $data['menuRight'][] = '<a href="' . au_route_partner('admin_page.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus" title="'.au_language_render('action.add').'"></i>
                           </a>';
        //=menuRight

        //menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        $data['urlSort'] = au_route_partner('admin_page.index', request()->except(['_token', '_pjax', 'sort_order']));

        $data['optionSort'] = $optionSort;
        //=menuSort

        //menuSearch
        $data['topMenuRight'][] = '
                <form action="' . au_route_partner('admin_page.index') . '" id="button_search">
                <div class="input-group input-group" style="width: 350px;">
                    <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . au_language_render('admin.page.search_place') . '" value="' . $keyword . '">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>';
        //=menuSearch

        return view($this->templatePathAdmin.'screen.list')
            ->with($data);
    }

    /*
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $page = [];
        $data = [
            'title'             => au_language_render('admin.page.add_new_title'),
            'subTitle'          => '',
            'title_description' => au_language_render('admin.page.add_new_des'),
            'icon'              => 'fa fa-plus',
            'languages'         => $this->languages,
            'page'              => $page,
            'url_action'        => au_route_partner('admin_page.create'),
        ];

        return view($this->templatePathAdmin.'screen.page')
            ->with($data);
    }

    /*
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();
        $langFirst = array_key_first(au_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['title'];
        $data['alias'] = au_word_format_url($data['alias']);
        $data['alias'] = au_word_limit($data['alias'], 100);
        $validator = Validator::make(
            $data,
            [
                'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100',
                'descriptions.*.title' => 'required|string|max:200',
                'descriptions.*.keyword' => 'nullable|string|max:200',
                'descriptions.*.description' => 'nullable|string|max:300',
            ],
            [
                'alias.regex' => au_language_render('admin.page.alias_validate'),
                'descriptions.*.title.required' => au_language_render('validation.required', ['attribute' => au_language_render('admin.page.title')]),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $dataInsert = [
            'image'    => $data['image'],
            'alias'    => $data['alias'],
            'status'   => !empty($data['status']) ? 1 : 0,
        ];
        $page = AdminPage::createPageAdmin($dataInsert);
        $dataDes = [];
        $languages = $this->languages;
        foreach ($languages as $code => $value) {
            $dataDes[] = [
                'page_id'     => $page->id,
                'lang'        => $code,
                'title'       => $data['descriptions'][$code]['title'],
                'keyword'     => $data['descriptions'][$code]['keyword'],
                'description' => $data['descriptions'][$code]['description'],
                'content'     => $data['descriptions'][$code]['content'],
            ];
        }
        AdminPage::insertDescriptionAdmin($dataDes);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $page->stores()->detach();
            if ($shopStore) {
                $page->stores()->attach($shopStore);
            }
        }

        au_clear_cache('cache_page');
        return redirect()->route('admin_page.index')->with('success', au_language_render('action.create_success'));
    }

    /*
     * Form edit
     */
    public function edit($id)
    {
        $page = AdminPage::getPageAdmin($id);
        if (!$page) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data = [
            'title' => au_language_render('action.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'languages' => $this->languages,
            'page' => $page,
            'url_action' => au_route_partner('admin_page.edit', ['id' => $page['id']]),
        ];
        return view($this->templatePathAdmin.'screen.page')
            ->with($data);
    }

    /*
     * update status
     */
    public function postEdit($id)
    {
        $page = AdminPage::getPageAdmin($id);
        if (!$page) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }

        $data = request()->all();
        $langFirst = array_key_first(au_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['title'];
        $data['alias'] = au_word_format_url($data['alias']);
        $data['alias'] = au_word_limit($data['alias'], 100);

        $validator = Validator::make(
            $data,
            [
                'descriptions.*.title' => 'required|string|max:200',
                'descriptions.*.keyword' => 'nullable|string|max:200',
                'descriptions.*.description' => 'nullable|string|max:300',
                'alias' => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100',
            ],
            [
                'alias.regex' => au_language_render('admin.page.alias_validate'),
                'descriptions.*.title.required' => au_language_render('validation.required', ['attribute' => au_language_render('admin.page.title')]),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        //Edit
        $dataUpdate = [
            'image' => $data['image'],
            'status' => empty($data['status']) ? 0 : 1,
        ];
        if (!empty($data['alias'])) {
            $dataUpdate['alias'] = $data['alias'];
        }
        $page->update($dataUpdate);
        $page->descriptions()->delete();
        $dataDes = [];
        foreach ($data['descriptions'] as $code => $row) {
            $dataDes[] = [
                'page_id'     => $id,
                'lang'        => $code,
                'title'       => $row['title'],
                'keyword'     => $row['keyword'],
                'description' => $row['description'],
                'content'     => $row['content'],
            ];
        }
        AdminPage::insertDescriptionAdmin($dataDes);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $page->stores()->detach();
            if ($shopStore) {
                $page->stores()->attach($shopStore);
            }
        }

        au_clear_cache('cache_page');
        return redirect()->route('admin_page.index')->with('success', au_language_render('action.edit_success'));
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
            AdminPage::destroy($arrID);
            au_clear_cache('cache_page');
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id)
    {
        return AdminPage::getPageAdmin($id);
    }
}
