<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Admin\Models\AdminNews;
use Validator;

class AdminNewsController extends RootAdminController
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
            'title'         => au_language_render('admin.news.list'),
            'subTitle'      => '',
            'icon'          => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_news.delete'),
            'removeList'    => 1, // 1 - Enable function delete list item
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
            'id'     => 'ID',
            'title'  => au_language_render('admin.news.title'),
            'image'  => au_language_render('admin.news.image'),
            'sort'   => au_language_render('admin.news.sort'),
            'status' => au_language_render('admin.news.status'),
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
            'title__desc' => au_language_render('filter_sort.title_desc'),
            'title__asc' => au_language_render('filter_sort.title_asc'),
        ];

        $dataSearch = [
            'keyword'    => $keyword,
            'sort_order' => $sort_order,
            'arrSort'    => $arrSort,
        ];
        $dataTmp = AdminNews::getNewsListAdmin($dataSearch);

        if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
            $arrId = $dataTmp->pluck('id')->toArray();
            // Only show store info if store is root
            $dataStores =  au_get_list_partner_of_news($arrId);
        }

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $dataMap = [
                'id' => $row['id'],
                'title' => $row['title'],
                'image' => au_image_render($row['image'], '50px', null, $row['title']),
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            ];

            if ((au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) && session('adminPartnerId') == AU_ID_ROOT) {
                // Only show store info if store is root
                if (!empty($dataStores[$row['id']])) {
                    $storeTmp = $dataStores[$row['id']]->pluck('code', 'id')->toArray();
                    $storeTmp = array_map(function ($code) {
                        return '<a target=_new href="'.au_get_domain_from_code($code).'">'.$code.'</a>';
                    }, $storeTmp);
                    $dataMap['shop_store'] = '<i class="nav-icon fab fa-shopify"></i> '.implode('<br><i class="nav-icon fab fa-shopify"></i> ', $storeTmp);
                } else {
                    $dataMap['shop_store'] = '';
                }
            }
            $dataMap['action'] = '<a href="' . au_route_partner('admin_news.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
            <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
            ';
            $dataTr[] = $dataMap;
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);


        //menuRight
        $data['menuRight'][] = '<a href="' . au_route_partner('admin_news.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus" title="'.au_language_render('action.add').'"></i>
                           </a>';
        //=menuRight

        //menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        $data['urlSort'] = au_route_partner('admin_news.index', request()->except(['_token', '_pjax', 'sort_order']));
        $data['optionSort'] = $optionSort;
        //=menuSort

        //menuSearch
        $data['topMenuRight'][] = '
                <form action="' . au_route_partner('admin_news.index') . '" id="button_search">
                    <div class="input-group input-group" style="width: 350px;">
                        <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . au_language_render('admin.news.search_place') . '" value="' . $keyword . '">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>';
        //=menuSearch


        return view($this->templatePathAdmin.'screen.list')
            ->with($data);
    }

    /**
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $news = [];
        $data = [
            'title'             => au_language_render('admin.news.add_new_title'),
            'subTitle'          => '',
            'title_description' => au_language_render('admin.news.add_new_des'),
            'icon'              => 'fa fa-plus',
            'languages'         => $this->languages,
            'news'              => $news,
            'url_action'        => au_route_partner('admin_news.create'),
        ];

        return view($this->templatePathAdmin.'screen.news')
            ->with($data);
    }

    /**
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
                'alias.regex' => au_language_render('admin.news.alias_validate'),
                'descriptions.*.title.required' => au_language_render('validation.required', ['attribute' => au_language_render('admin.news.title')]),
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $dataInsert = [
            'image'    => $data['image'],
            'sort'     => $data['sort'],
            'alias'    => $data['alias'],
            'status'   => !empty($data['status']) ? 1 : 0,
        ];
        $news = AdminNews::createNewsAdmin($dataInsert);
        $id = $news->id;
        $dataDes = [];
        $languages = $this->languages;
        foreach ($languages as $code => $value) {
            $dataDes[] = [
                'news_id'     => $id,
                'lang'        => $code,
                'title'       => $data['descriptions'][$code]['title'],
                'keyword'     => $data['descriptions'][$code]['keyword'],
                'description' => $data['descriptions'][$code]['description'],
                'content'     => $data['descriptions'][$code]['content'],
            ];
        }
        AdminNews::insertDescriptionAdmin($dataDes);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $news->stores()->detach();
            if ($shopStore) {
                $news->stores()->attach($shopStore);
            }
        }

        au_clear_cache('cache_news');

        return redirect()->route('admin_news.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $news = AdminNews::getNewsAdmin($id);
        if (!$news) {
            return redirect()->route('admin.data_not_found')->with(['url' => url()->full()]);
        }
        $data = [
            'title'             => au_language_render('admin.news.edit'),
            'subTitle'          => '',
            'title_description' => '',
            'icon'              => 'fa fa-edit',
            'languages'         => $this->languages,
            'news'              => $news,
            'url_action'        => au_route_partner('admin_news.edit', ['id' => $news['id']]),
        ];
        return view($this->templatePathAdmin.'screen.news')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $news = AdminNews::getNewsAdmin($id);
        if (!$news) {
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
                'alias.regex' => au_language_render('admin.news.alias_validate'),
                'descriptions.*.title.required' => au_language_render('validation.required', ['attribute' => au_language_render('admin.news.title')]),
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
            'alias' => $data['alias'],
            'sort' => $data['sort'],
            'status' => !empty($data['status']) ? 1 : 0,
        ];

        $news->update($dataUpdate);
        $news->descriptions()->delete();
        $dataDes = [];
        foreach ($data['descriptions'] as $code => $row) {
            $dataDes[] = [
                'news_id' => $id,
                'lang' => $code,
                'title' => $row['title'],
                'keyword' => $row['keyword'],
                'description' => $row['description'],
                'content' => $row['content'],
            ];
        }
        AdminNews::insertDescriptionAdmin($dataDes);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            // If multi-store
            $shopStore        = $data['shop_store'] ?? [];
            $news->stores()->detach();
            if ($shopStore) {
                $news->stores()->attach($shopStore);
            }
        }

        au_clear_cache('cache_news');

        return redirect()->route('admin_news.index')->with('success', au_language_render('action.edit_success'));
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
            AdminNews::destroy($arrID);
            au_clear_cache('cache_news');

            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    /**
     * Check permisison item
     */
    public function checkPermisisonItem($id)
    {
        return AdminNews::getNewsAdmin($id);
    }
}
