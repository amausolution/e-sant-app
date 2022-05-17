<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\FegguPartnerCss;
use Feggu\Core\Admin\Models\AdminTemplate;
use Feggu\Core\Admin\Models\AdminPartner;
class AdminPartnerCssController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Form edit
     */
    public function index()
    {
        $id = session('adminPartnerId');
        ;
        $cssContent = FegguPartnerCss::where('partner_id', $id)->first();
        if (!$cssContent) {
            return 'no data';
        }
        $data = [
            'title' => au_language_render('partner.admin.css'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'css' => $cssContent->css,
            'url_action' => au_route_admin('admin_partner_css.index'),
        ];
        return view($this->templatePathAdmin.'screen.partner_css')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit()
    {
        $id = session('adminPartnerId');
        ;
        $cssContent = FegguPartnerCss::where('partner_id', $id)->first();
        $cssContent->css = request('css');
        $cssContent->save();
        return redirect()->route('admin_partner_css.index')->with('success', au_language_render('action.edit_success'));
    }
}
