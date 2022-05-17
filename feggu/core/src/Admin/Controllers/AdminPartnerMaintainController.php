<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminPartner;
use Feggu\Core\Partner\Models\FegguLanguage;
use Validator;

class AdminPartnerMaintainController extends RootAdminController
{
    public $languages;

    public function __construct()
    {
        parent::__construct();
        $this->languages = FegguLanguage::getListActive();
    }

    /**
     * Form edit
     */
    public function index()
    {
        $id = session('adminPartnerId');
        $maintain = AdminPartner::find($id);
        if ($maintain === null) {
            return 'no data';
        }
        $data = [
            'title' => au_language_render('admin.maintain.title'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'languages' => $this->languages,
            'maintain' => $maintain,
            'url_action' => au_route_partner('admin_partner_maintain.index'),
        ];
        return view($this->templatePathAdmin.'screen.store_maintain')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit()
    {
        $id = session('adminPartnerId');
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'descriptions.*.maintain_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        foreach ($data['descriptions'] as $code => $row) {
            $dataUpdate = [
                'partnerId' => $id,
                'lang' => $code,
                'name' => 'maintain_content',
                'value' => $row['maintain_content'],
            ];
            AdminPartner::updateDescription($dataUpdate);

            $dataUpdate = [
                'partnerId' => $id,
                'lang' => $code,
                'name' => 'maintain_note',
                'value' => $row['maintain_note'],
            ];
            AdminPartner::updateDescription($dataUpdate);
        }
//
        return redirect()->route('admin_partner_maintain.index')->with('success', au_language_render('action.edit_success'));
    }
}
