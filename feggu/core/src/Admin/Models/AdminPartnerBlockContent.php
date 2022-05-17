<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\FegguPartnerBlockContent;

class AdminPartnerBlockContent extends FegguPartnerBlockContent
{
    /**
     * Get blockContent detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public function getStoreBlockContentAdmin($id, $partnerId = null)
    {
        $data  = $this->where('id', $id);
        if ($partnerId) {
            $data = $data->where('partner_id', $partnerId);
        }
        return $data->first();
    }

    /**
     * Get list blockContent in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public function getStoreBlockContentListAdmin($partnerId = null)
    {
        if ($partnerId) {
            $data = $this->where('partner_id', $partnerId)
                ->orderBy('id', 'desc');
        } else {
            $data = $this->orderBy('id', 'desc');
        }
        return $data->paginate(20);
    }

    /**
     * Create a new blockContent
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createStoreBlockContentAdmin(array $dataInsert)
    {
        return self::insert($dataInsert);
    }
}
