<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use App\Product;
use App\Rules\FileTypeValidate;
use Feggu\Core\Admin\Models\AdminCategoryAnalysis;
use Feggu\Core\Partner\Models\CategoryAnalysis;
use Feggu\Core\Partner\Models\CategoryAnalysisDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CategoryAnalysisController extends RootAdminController
{
    public $languages;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title'      => __('List Category Analysis'),
            'title_description' => __('Display Category Analysis')
        ];
        return view($this->templatePathAdmin.'screen.category_analysis.category_analysis')
            ->with($data);
    }

    public function create()
    {
        $data = [
            'title'      => __('New Category Analysis'),
            'languages'  => $this->languages,
            'title_description' => __('Create a new category analysis'),

            'category'=>[],
        ];
        return view($this->templatePathAdmin.'screen.category_analysis_add')
            ->with($data);
    }

    public function store()
    {
        // dd(request()->all());
        $data = request()->all();
        $validator = Validator::make(
            $data,
            [
                'sort'                   => 'numeric|min:0',
                //'alias'                  => 'required|unique:"'.AdminCategoryPartner::class.'",alias|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100',
                'descriptions.*.title'   => 'required|string|max:200',
                'descriptions.*.description' => 'nullable|string|max:300',
            ],
            [
                'descriptions.*.title.required' => au_language_render('validation.required', ['attribute' => au_language_render('admin.category.title')]),
                'alias.regex' => au_language_render('admin.category.alias_validate'),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $langFirst = array_key_first(au_language_all()->toArray()); //get first code language active
        $data['alias'] = !empty($data['alias'])?$data['alias']:$data['descriptions'][$langFirst]['title'];
        $data['alias'] = au_word_format_url($data['alias']);
        $data['alias'] = au_word_limit($data['alias'], 100);


        $dataInsert = [
            'image'    => $data['image'],
            'alias'    => $data['alias'],
            'status'   => !empty($data['status']) ? 1 : 0,
            'sort'     => (int) $data['sort'],
        ];
        $category = AdminCategoryPartner::createCategoryAdmin($dataInsert);
        $dataDes = [];
        $languages = $this->languages;
        foreach ($languages as $code => $value) {
            $dataDes[] = [
                'category_id' => $category->id,
                'lang'        => $code,
                'title'       => $data['descriptions'][$code]['title'],
                'description' => $data['descriptions'][$code]['description'],
            ];
        }
        AdminCategoryPartner::insertDescriptionAdmin($dataDes);


        au_clear_cache('cache_category_partner');

        return redirect()->route('admin_category_partner.index')->with('success', au_language_render('action.create_success'));

    }

    public function edit($id)
    {
        $category = AdminCategoryAnalysis::getCategoryAdmin($id);
        $data = [
            'title'      => __('Edit Category Analysis'),
            'languages'  => $this->languages,
            'category'=> $category,
            'title_description' => __('Edite category analysis')
        ];
        return view($this->templatePathAdmin.'screen.category_analysis.category_analysis_add')
            ->with($data);
    }

    public function update($id)
    {
        $category = AdminCategoryAnalysis::getCategoryAdmin($id);
        if (!$category) {
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
                'sort'                   => 'numeric|min:0',
                //'alias'                  => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100|unique:"'.AdminCategoryPartner::class.'",alias,' . $id . '',
                'descriptions.*.title'   => 'required|string|max:200',
                'descriptions.*.description' => 'nullable|string|max:300',
            ],
            [
                'descriptions.*.title.required' => au_language_render('validation.required', ['attribute' => au_language_render('admin.category.title')]),
                'alias.regex'                   => au_language_render('admin.category.alias_validate'),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        //Edit
        $dataUpdate = [
            'image'    => $data['image'],
            'alias'    => $data['alias'],
            'sort'     => $data['sort'],
            'status'   => empty($data['status']) ? 0 : 1,
        ];

        $category->update($dataUpdate);
        $category->descriptions()->delete();
        $dataDes = [];
        foreach ($data['descriptions'] as $code => $row) {
            $dataDes[] = [
                'category_id' => $id,
                'lang'        => $code,
                'title'       => $row['title'],
                'description' => $row['description'],
            ];
        }
        AdminCategoryAnalysis::insertDescriptionAdmin($dataDes);

        au_clear_cache('cache_category');

        //
        return redirect()->route('admin_category_partner.index')->with('success', au_language_render('action.edit_success'));
    }

    public function deleteList()
    {

    }

    public function show($id)
    {
        $category = AdminCategoryAnalysis::getCategoryAdmin($id);
        $data = [
            'title'      => __('Show Detail Category Analysis'),
            'category'=> $category,
            'title_description' => au_language_render('feggu.category_partner_edit'),
            'specifications'=>$category->specifications,
        ];
        return view($this->templatePathAdmin.'screen.category_analysis.category_analysis_show')
            ->with($data);
    }

    public function add(Request $request)
    {
        $validation_rule = [
            'category_id'=>'required',
            'specification'         => 'sometimes|required|array',
            'specification.*.name'  => 'required_with:specification',
        ];


        $validator = Validator::make($request->all(), $validation_rule, [
            'specification.*.name.required'   =>  'All specification name is required',
            'specification.*.value'           =>  'All specification value is required',
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
       // dd($request->specification);
        $reload = true;
        if ($request->specification) {
            foreach($request->specification as $key => $name){
                $value = new CategoryAnalysisDetail();
                $value->category_analysis_id   = $request->category_id;
                $value->title        = $name['name'];
                $value->save();
            }
            $status='success';
            $message = __('Product Added Successfully');

        }else{
            $reload = false;
            $message = __('please add specification befor');
            $status='error';
        }
        $reload = false;

        //dd($a);
        return response()->json(['status'=>$status, 'message'=>$message, 'reload'=>$reload]);
    }
}
