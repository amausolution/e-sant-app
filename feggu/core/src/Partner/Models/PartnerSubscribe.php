<?php

namespace Feggu\Core\Partner\Models;

use Feggu\Core\Partner\Models\FegguSubscribe;

class PartnerSubscribe extends FegguSubscribe
{
    /**
     * Get subcribe detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getSubscribePartner($id)
    {
        return self::where('id', $id)
        ->where('partner_id', session('adminPartnerId'))
        ->first();
    }

    /**
     * Get list subcribe in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getSubscribeListPartner(array $dataSearch)
    {
        $sort_order       = $dataSearch['sort_order'] ?? '';
        $arrSort          = $dataSearch['arrSort'] ?? '';
        $subcribeList = (new PartnerSubscribe)
            ->where('partner_id', session('adminPartnerId'));

        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $subcribeList = $subcribeList->orderBy($field, $sort_field);
        } else {
            $subcribeList = $subcribeList->orderBy('id', 'desc');
        }
        $subcribeList = $subcribeList->paginate(20);

        return $subcribeList;
    }

    /**
     * Create a new subcribe
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createSubscribePartner(array $dataInsert)
    {
        $dataInsert = au_clean($dataInsert);
        return self::create($dataInsert);
    }
}
