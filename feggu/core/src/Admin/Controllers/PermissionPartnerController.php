<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\PartnerPermission;
use Illuminate\Support\Str;
use Validator;

class PermissionPartnerController extends RootAdminController
{
    public $routePartner;

    public function __construct()
    {
        parent::__construct();

        $routepartner = app()->routes->getRoutes();
       // dd($routepartner);
        $routePartner = [];
        foreach ($routepartner as $route) {
            if (Str::startsWith($route->uri(), AU_PARTNER_PREFIX)) {
                $prefix = AU_PARTNER_PREFIX?$route->getPrefix():ltrim($route->getPrefix(), '/');
                $routePartner[$prefix] = [
                    'uri'    => 'ANY::' . $prefix . '/*',
                    'name'   => $prefix . '/*',
                    'method' => 'ANY',
                ];
                foreach ($route->methods as $key => $method) {
                    if ($method != 'HEAD' && !collect($this->without())->first(function ($exp) use ($route) {
                        return Str::startsWith($route->uri, $exp);
                    })) {
                        $routePartner[] = [
                            'uri'    => $method . '::' . $route->uri,
                            'name'   => $route->uri,
                            'method' => $method,
                        ];
                    }
                }
            }
        }

        $this->routePartner = $routePartner;
    }

    public function index()
    {
        $data = [
            'title' => au_language_render('admin.permission.list'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_permission_partner.delete'),
            'removeList' => 1, // 1 - Enable function delete list item
            'buttonRefresh' => 1, // 1 - Enable button refresh
            'buttonSort' => 1, // 1 - Enable button sort
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
            'id' => 'ID',
            'slug' => au_language_render('admin.permission.slug'),
            'name' => au_language_render('admin.permission.name'),
            'http_path' => au_language_render('admin.permission.http_path'),
            'updated_at' => au_language_render('admin.updated_at'),
            'action' => au_language_render('action.title'),
        ];
        $sort_order = au_clean(request('sort_order') ?? 'id_desc');
        $arrSort = [
            'id__desc' => au_language_render('filter_sort.id_desc'),
            'id__asc' => au_language_render('filter_sort.id_asc'),
            'name__desc' => au_language_render('filter_sort.name_desc'),
            'name__asc' => au_language_render('filter_sort.name_asc'),
        ];
        $obj = new PartnerPermission;
        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $obj = $obj->orderBy($field, $sort_field);
        } else {
            $obj = $obj->orderBy('id', 'desc');
        }
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $permissions = '';
            if ($row['http_uri']) {
                $methods = array_map(function ($value) {
                    $route = explode('::', $value);
                    $methodStyle = '';
                    if ($route[0] == 'ANY') {
                        $methodStyle = '<span class="badge badge-info">' . $route[0] . '</span>';
                    } elseif ($route[0] == 'POST') {
                        $methodStyle = '<span class="badge badge-warning">' . $route[0] . '</span>';
                    } else {
                        $methodStyle = '<span class="badge badge-primary">' . $route[0] . '</span>';
                    }
                    return $methodStyle . ' <code>' . $route[1] . '</code>';
                }, explode(',', $row['http_uri']));
                $permissions = implode('<br>', $methods);
            }
            $dataTr[] = [
                'id' => $row['id'],
                'slug' => $row['slug'],
                'name' => $row['name'],
                'permission' => $permissions,
                'updated_at' => $row['updated_at'],
                'action' => '
                    <a href="' . au_route_partner('admin_permission_partner.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;
                    <span onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>'
                ,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menuRight
        $data['menuRight'][] = '<a href="' . au_route_partner('admin_permission_partner.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus" title="'.au_language_render('action.add').'"></i>
                           </a>';
        //=menuRight

        //menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        $data['urlSort'] = au_route_partner('admin_permission_partner.index', request()->except(['_token', '_pjax', 'sort_order']));
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
            'title' => au_language_render('admin.permission.admin.add_new_title'),
            'subTitle' => '',
            'title_description' => au_language_render('admin.permission.admin.add_new_des'),
            'icon' => 'fa fa-plus',
            'permission' => [],
            'routePartner' => $this->routePartner,
            'url_action' => au_route_partner('admin_permission_partner.create'),

        ];

        return view($this->templatePathAdmin.'screen.permission_partner')
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
            'name' => 'required|string|max:50|unique:"'.PartnerPermission::class.'",name',
            'slug' => 'required|regex:/(^([0-9A-Za-z\._\-]+)$)/|unique:"'.PartnerPermission::class.'",slug|string|max:50|min:3',
        ], [
            'slug.regex' => au_language_render('admin.permission.slug_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataInsert = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'http_uri' => implode(',', ($data['http_uri'] ?? [])),
        ];

        $permission = PartnerPermission::createPermission($dataInsert);

        return redirect()->route('admin_permission_partner.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $permission = PartnerPermission::find($id);
        if ($permission === null) {
            return 'no data';
        }
        $data = [
            'title' => au_language_render('action.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'permission' => $permission,
            'routePartner' => $this->routePartner,
            'url_action' => au_route_partner('admin_permission_partner.edit', ['id' => $permission['id']]),
        ];
        return view($this->templatePathAdmin.'screen.permission_partner')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $permission = PartnerPermission::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required|string|max:50|unique:"'.PartnerPermission::class.'",name,' . $permission->id . '',
            'slug' => 'required|regex:/(^([0-9A-Za-z\._\-]+)$)/|unique:"'.PartnerPermission::class.'",slug,' . $permission->id . '|string|max:50|min:3',
        ], [
            'slug.regex' => au_language_render('admin.permission.slug_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'http_uri' => implode(',', ($data['http_uri'] ?? [])),
        ];
        $permission->update($dataUpdate);
//
        return redirect()->route('admin_permission_partner.index')->with('success', au_language_render('action.edit_success'));
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
            PartnerPermission::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }

    public function without()
    {
        $prefix = AU_PARTNER_PREFIX?AU_PARTNER_PREFIX.'/':'';
        return [
            $prefix . 'login',
            $prefix . 'logout',
            $prefix . 'forgot',
            $prefix . 'deny',
            $prefix . 'locale',
            $prefix . 'uploads',
        ];
    }
}
