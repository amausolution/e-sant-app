<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\FegguCurrency;
use Validator;

class AdminCurrencyController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => au_language_render('admin.currency.list'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'urlDeleteItem' => au_route_partner('admin_currency.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
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
            'name' => au_language_render('admin.currency.name'),
            'code' => au_language_render('admin.currency.code'),
            'symbol' => au_language_render('admin.currency.symbol'),
            'exchange_rate' => au_language_render('admin.currency.exchange_rate'),
            'precision' => au_language_render('admin.currency.precision'),
            'symbol_first' => au_language_render('admin.currency.symbol_first'),
            'thousands' => au_language_render('admin.currency.thousands'),
            'sort' => au_language_render('admin.currency.sort'),
            'status' => au_language_render('admin.currency.status'),
            'action' => au_language_render('action.title'),
        ];

        $sort_order = au_clean(request('sort_order') ?? 'id_desc');
        $keyword    = au_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc' => au_language_render('filter_sort.id_desc'),
            'id__asc' => au_language_render('filter_sort.id_asc'),
            'name__desc' => au_language_render('filter_sort.name_desc'),
            'name__asc' => au_language_render('filter_sort.name_asc'),
        ];
        $obj = new FegguCurrency;
        if ($keyword) {
            $obj = $obj->whereRaw('(code = "' . $keyword . '" OR name like "%' . $keyword . '%" )');
        }

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
                'name' => $row['name'],
                'code' => $row['code'],
                'symbol' => $row['symbol'],
                'exchange_rate' => $row['exchange_rate'],
                'precision' => $row['precision'],
                'symbol_first' => $row['symbol_first'],
                'thousands' => $row['thousands'],
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => '
                    <a href="' . au_route_partner('admin_currency.edit', ['id' => $row['id']]) . '"><span title="' . au_language_render('action.edit') . '" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-edit"></i></span></a>&nbsp;

                  <span ' . (in_array($row['id'], AU_GUARD_CURRENCY) ? "style='display:none'" : "") . ' onclick="deleteItem(' . $row['id'] . ');"  title="' . au_language_render('action.delete') . '" class="btn btn-flat btn-sm btn-danger"><i class="fas fa-trash-alt"></i></span>
                  ',
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->templatePathAdmin.'component.pagination');
        $data['resultItems'] = au_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menuRight
        $data['menuRight'][] = '<a href="' . au_route_partner('admin_currency.create') . '" class="btn  btn-success  btn-flat" title="New" id="button_create_new">
        <i class="fa fa-plus" title="'.au_language_render('action.add').'"></i>
                           </a>';
        //=menuRight

        //menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }

        $data['urlSort'] = au_route_partner('admin_currency.index', request()->except(['_token', '_pjax', 'sort_order']));

        $data['optionSort'] = $optionSort;
        //=menuSort

        //menuSearch
        $data['topMenuRight'][] = '
                <form action="' . au_route_partner('admin_currency.index') . '" id="button_search">
                <div class="input-group input-group" style="width: 350px;">
                    <input type="text" name="keyword" class="form-control rounded-0 float-right" placeholder="' . au_language_render('search.placeholder') . '" value="' . $keyword . '">
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
        $data = [
            'title' => au_language_render('admin.currency.add_new_title'),
            'subTitle' => '',
            'title_description' => au_language_render('admin.currency.add_new_des'),
            'icon' => 'fa fa-plus',
            'currency' => [],
            'url_action' => au_route_partner('admin_currency.create'),
        ];
        return view($this->templatePathAdmin.'screen.currency')
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
        $validator = Validator::make($data, [
            'symbol' => 'required',
            'exchange_rate' => 'required|numeric|gt:0',
            'precision' => 'required',
            'symbol_first' => 'required',
            'thousands' => 'required',
            'sort' => 'numeric|min:0',
            'name' => 'required|string|max:100',
            'code' => 'required|unique:"'.FegguCurrency::class.'",code',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataInsert = [
            'name' => $data['name'],
            'code' => $data['code'],
            'symbol' => $data['symbol'],
            'exchange_rate' => $data['exchange_rate'],
            'precision' => $data['precision'],
            'symbol_first' => $data['symbol_first'],
            'thousands' => $data['thousands'],
            'status' => empty($data['status']) ? 0 : 1,
            'sort' => (int) $data['sort'],
        ];
        FegguCurrency::create($dataInsert);

        return redirect()->route('admin_currency.index')->with('success', au_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $currency = FegguCurrency::find($id);
        if ($currency === null) {
            return 'no data';
        }
        $data = [
            'title' => au_language_render('action.edit'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'currency' => $currency,
            'url_action' => au_route_partner('admin_currency.edit', ['id' => $currency['id']]),
        ];
        return view($this->templatePathAdmin.'screen.currency')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $currency = FegguCurrency::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($data, [
            'symbol' => 'required',
            'exchange_rate' => 'required|numeric|gt:0',
            'precision' => 'required',
            'symbol_first' => 'required',
            'thousands' => 'required',
            'sort' => 'numeric|min:0',
            'name' => 'required|string|max:100',
            'code' => 'required|unique:"'.FegguCurrency::class.'",code,' . $currency->id . ',id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'name' => $data['name'],
            'code' => $data['code'],
            'symbol' => $data['symbol'],
            'exchange_rate' => $data['exchange_rate'],
            'precision' => $data['precision'],
            'symbol_first' => $data['symbol_first'],
            'thousands' => $data['thousands'],
            'sort' => (int) $data['sort'],

        ];

        //Check status before change
        $check = FegguCurrency::where('status', 1)->where('code', '<>', $data['code'])->count();
        if ($check) {
            $dataUpdate['status'] = empty($data['status']) ? 0 : 1;
        } else {
            $dataUpdate['status'] = 1;
        }
        //End check status

        $obj = FegguCurrency::find($id);
        $obj->update($dataUpdate);

//
        return redirect()->route('admin_currency.index')->with('success', au_language_render('action.edit_success'));
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
            $arrID = array_diff($arrID, AU_GUARD_CURRENCY);
            FegguCurrency::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
