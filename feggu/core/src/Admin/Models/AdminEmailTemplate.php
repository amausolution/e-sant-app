<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\FegguEmailTemplate;

class AdminEmailTemplate extends FegguEmailTemplate
{
    protected static $getListTitleAdmin = null;
    protected static $getListEmailTemplateGroupByParentAdmin = null;
    /**
     * Get news detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getEmailTemplateAdmin($id)
    {
        return self::where('id', $id)
        ->where('partner_id', session('adminPartnerId'))
        ->first();
    }

    /**
     * Get list news in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getEmailTemplateListAdmin(array $dataSearch)
    {
        $keyword          = $dataSearch['keyword'] ?? '';
        $sort_order       = $dataSearch['sort_order'] ?? '';
        $arrSort          = $dataSearch['arrSort'] ?? '';

        $newsList = (new FegguEmailTemplate)
            ->where('partner_id', session('adminPartnerId'));

        if ($keyword) {
            $newsList = $newsList->where(function ($sql) {
                $sql->where('name', 'like', '%' . $keyword . '%');
            });
        }

        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $newsList = $newsList->orderBy($field, $sort_field);
        } else {
            $newsList = $newsList->orderBy('id', 'desc');
        }
        $newsList = $newsList->paginate(20);

        return $newsList;
    }

    /**
     * Create a new news
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createEmailTemplateAdmin(array $dataInsert)
    {
        return self::create($dataInsert);
    }
}
