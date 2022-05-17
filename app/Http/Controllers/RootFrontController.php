<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class RootFrontController extends Controller
{
    public $templatePath;
    public $templateFile;
    public function __construct()
    {
        $this->templatePath = 'templates.' . au_partner('template');
        $this->templateFile = 'templates/' . au_partner('template');
    }


    /**
     * Default page not found
     *
     * @return  [type]  [return description]
     */
    public function pageNotFound()
    {
        sc_check_view( $this->templatePath . '.notfound');
        return view(
             $this->templatePath . '.notfound',
            [
            'title' => au_language_render('partner.page_not_found_title'),
            'msg' => au_language_render('partner.page_not_found'),
            'description' => '',
            'keyword' => ''
            ]
        );
    }

    /**
     * Default item not found
     *
     * @return  [view]
     */
    public function itemNotFound()
    {
        sc_check_view( $this->templatePath . '.notfound');
        return view(
             $this->templatePath . '.notfound',
            [
                'title' => au_language_render('partner.data_not_found_title'),
                'msg' => au_language_render('partner.data_not_found'),
                'description' => '',
                'keyword' => '',
            ]
        );
    }
}
