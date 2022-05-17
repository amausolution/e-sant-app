<?php
namespace Feggu\Core\Partner\Controllers;

use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\Languages;
use Feggu\Core\Partner\Models\FegguLanguage;
use Validator;

class PartnerLanguageManagerController extends RootPartnerController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $lang = request('lang');
        $position = request('position');
        $keyword = request('keyword');
        $languages = FegguLanguage::getListAll();
        $positionLang = Languages::getPosition();
        $languagesPosition = Languages::getLanguagesPosition($lang, $position, $keyword);

        $codeLanguages = FegguLanguage::getCodeAll();
        if (!in_array($lang, array_keys($codeLanguages))) {
            $languagesPositionEL =   [];
        } else {
            $languagesPositionEL = Languages::getLanguagesPosition('en', $position, $keyword);
        }
        $arrayKeyLanguagesPosition = array_keys($languagesPosition);
        $arrayKeyLanguagesPositionEL = array_keys($languagesPositionEL);
        $arrayKeyDiff = array_diff($arrayKeyLanguagesPositionEL, $arrayKeyLanguagesPosition);
        $urlUpdateData = au_route_partner('partner_language_manager.update');
        $data = [
            'languages' => $languages,
            'lang' => $lang,
            'positionLang' => $positionLang,
            'position' => $position,
            'keyword' => $keyword,
            'languagesPosition' => $languagesPosition,
            'languagesPositionEL' => $languagesPositionEL,
            'arrayKeyDiff' => $arrayKeyDiff,
            'urlUpdateData' => $urlUpdateData,
            'title' => au_language_render('partner.language_manager.title'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'css' => '',
            'js' => '',
            'layout' => 'index',
        ];


        return view($this->templatePathPartner.'screen.language_manager')
            ->with($data);
    }

    /**
     * Update data
     *
     * @return void
     */
    public function postUpdate()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => au_language_render('partner.method_not_allow')]);
        } else {
            $data = request()->all();
            $lang = au_clean($data['lang']);
            $name = au_clean($data['name']);
            $value = au_clean($data['value']);
            $position = au_clean($data['pk']);
            $languages = FegguLanguage::getCodeAll();
            if (!in_array($lang, array_keys($languages))) {
                return response()->json(['error' => 1, 'msg' => au_language_render('partner.method_not_allow')]);
            }
            if ($position) {
                Languages::updateOrCreate(
                    ['location' => $lang, 'code' => $name],
                    ['text' => $value, 'position' => $position],
                );
            } else {
                Languages::updateOrCreate(
                    ['location' => $lang, 'code' => $name],
                    ['text' => $value],
                );
            }

            return response()->json(['error' => 0, 'msg' => au_language_render('action.update_success')]);
        }
    }

    /**
     * Screen add new record language
     *
     * @return void
     */
    public function add()
    {
        $languages = FegguLanguage::getListAll();
        $positionLang = Languages::getPosition();
        $data = [
            'title' => au_language_render('partner.language_manager.add'),
            'positionLang' => $positionLang,
            'languages' => $languages,
        ];
        return view($this->templatePathPartner.'screen.language_manager_add')
            ->with($data);
    }

    /**
     * Add new record for language
     *
     * @return void
     */
    public function postAdd()
    {
        $data = request()->all();
        $validator = Validator::make(
            $data,
            [
                'text'         => 'required',
                'position' => 'required_without:position_new',
                'code'         => 'required|unique:"'.Languages::class.'",code|string|max:100',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $dataInsert = [
            'code' => trim($data['code']),
            'text' => trim($data['text']),
            'position' => trim(empty($data['position_new']) ? $data['position'] : $data['position_new']),
            'location' => 'fr',
        ];
        Languages::insert($dataInsert);

        return redirect(au_route_partner('partner_language_manager.index'))->with('success', au_language_render('action.create_success'));
    }
}
