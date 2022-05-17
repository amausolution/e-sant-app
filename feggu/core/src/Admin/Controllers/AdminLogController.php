<?php
namespace Feggu\Core\Admin\Controllers;

use Feggu\Core\Admin\Models\AdminLog;
use Feggu\Core\Admin\Models\AdminUser;
use App\Http\Controllers\RootAdminController;

class AdminLogController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title' => au_language_render('admin.log.list'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_log.delete'),
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
            'user' => au_language_render('admin.log.user'),
            'method' => au_language_render('admin.log.method'),
            'path' => au_language_render('admin.log.path'),
            'ip' => au_language_render('admin.log.ip'),
            'user_agent' => au_language_render('admin.log.user_agent'),
            'input' => au_language_render('admin.log.input'),
            'created_at' => au_language_render('admin.log.created_at'),
            'action' => au_language_render('action.title'),
        ];

        $sort_order = au_clean(request('sort_order') ?? 'id_desc');
        $arrSort = [
            'id__desc' => au_language_render('filter_sort.id_desc'),
            'id__asc' => au_language_render('filter_sort.id_asc'),
            'user_id__desc' => au_language_render('filter_sort.value_desc', ['value' => 'ID']),
            'user_id__asc' => au_language_render('filter_sort.value_asc', ['value' => 'ID']),
            'path__desc' => au_language_render('filter_sort.alpha_desc', ['alpha' => 'path']),
            'path__asc' => au_language_render('filter_sort.alpha_asc', ['alpha' => 'path']),
            'user_agent__desc' => au_language_render('filter_sort.alpha_desc', ['alpha' => 'User agent']),
            'user_agent__asc' => au_language_render('filter_sort.alpha_asc', ['alpha' => 'User agent']),
            'method__desc' => au_language_render('filter_sort.alpha_desc', ['alpha' => 'Method']),
            'method__asc' => au_language_render('filter_sort.alpha_asc', ['alpha' => 'Method']),
            'ip__desc' => au_language_render('filter_sort.alpha_desc', ['alpha' => 'Ip']),
            'ip__asc' => au_language_render('filter_sort.alpha_asc', ['alpha' => 'Ip']),

        ];
        $obj = new AdminLog;

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
            $dataTr[] = [
                'id' => $row['id'],
                'user_id' => ($user = AdminUser::find($row['user_id'])) ? $user->name : 'N/A',
                'method' => '<span class="badge bg-' . (AdminLog::$methodColors[$row['method']] ?? '') . '">' . $row['method'] . '</span>',
                'path' => '<code>' . $row['path'] . '</code>',
                'ip' => $row['ip'],
                'user_agent' => $row['user_agent'],
                'input' => htmlspecialchars($row['input']),
                'created_at' => $row['created_at'],
                'action' => '
                  <span  onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menuSearch
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        $data['optionSort'] = $optionSort;
        $data['urlSort'] = au_route_partner('admin_log.index', request()->except(['_token', '_pjax', 'sort_order']));
        //=menuSort

        return view($this->templatePathAdmin.'screen.list')
            ->with($data);
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
            AdminLog::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
