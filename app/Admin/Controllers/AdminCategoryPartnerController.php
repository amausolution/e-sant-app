<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminCategoryPartner;
use Feggu\Core\Partner\Models\FegguCategoryPartner;
use Feggu\Core\Partner\Models\FegguLanguage;
use Illuminate\Http\Request;
use Validator;

class AdminCategoryPartnerController extends RootAdminController
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
            'title'      => au_language_render('feggu.category_partner_title'),
            'languages'  => $this->languages,
            'title_description' => au_language_render('feggu.category_partner')
        ];
        return view($this->templatePathAdmin.'screen.category_partner')
            ->with($data);
    }

    public function create()
    {
        $data = [
            'title'      => au_language_render('feggu.category_partner_new'),
            'languages'  => $this->languages,
            'title_description' => au_language_render('feggu.category_partner_creat'),

            'category'=>[],
        ];
        return view($this->templatePathAdmin.'screen.category_partner_add')
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
                'alias'                  => 'required|unique:"'.AdminCategoryPartner::class.'",alias|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100',
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
        $category = AdminCategoryPartner::getCategoryAdmin($id);
        $data = [
            'title'      => au_language_render('feggu.category_partner_title'),
            'languages'  => $this->languages,
            'category'=> $category,
            'title_description' => au_language_render('feggu.category_partner_edit')
        ];
        return view($this->templatePathAdmin.'screen.category_partner_add')
            ->with($data);
    }

    public function update($id)
    {
        $category = AdminCategoryPartner::getCategoryAdmin($id);
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
                'alias'                  => 'required|regex:/(^([0-9A-Za-z\-_]+)$)/|string|max:100|unique:"'.AdminCategoryPartner::class.'",alias,' . $id . '',
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
        AdminCategoryPartner::insertDescriptionAdmin($dataDes);

        au_clear_cache('cache_category');

        //
        return redirect()->route('admin_category_partner.index')->with('success', au_language_render('action.edit_success'));
    }

    public function deleteList()
    {

    }

}
