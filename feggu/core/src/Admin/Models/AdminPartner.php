<?php
namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\FegguPartnerDescription;

class AdminPartner extends FegguPartner
{

    /**
     * Get all template used
     *
     * @return  [type]  [return description]
     */
    public static function getAllTemplateUsed()
    {
        return self::pluck('template')->all();
    }

    public static function insertDescription(array $data)
    {
        return FegguPartnerDescription::insert($data);
    }

    /**
     * Update description
     *
     * @param   array  $data  [$data description]
     *
     * @return  [type]        [return description]
     */
    public static function updateDescription(array $data)
    {
        $checkDes = FegguPartnerDescription::where('partner_id', $data['partnerId'])
        ->where('lang', $data['lang'])
        ->first();
        if ($checkDes) {
            return FegguPartnerDescription::where('partner_id', $data['partnerId'])
            ->where('lang', $data['lang'])
            ->update([$data['name'] => $data['value']]);
        } else {
            return FegguPartnerDescription::insert(
                [
                    'partner_id' => $data['partnerId'],
                    'lang' => $data['lang'],
                    $data['name'] => $data['value'],
                ]
            );
        }
    }
}
