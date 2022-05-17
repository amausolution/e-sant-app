<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\FegguLink;
use Feggu\Core\Partner\Models\FegguLinkStore;

class AdminLink extends FegguLink
{
    /**
     * Get link detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getLinkAdmin($id, $partnerId = null)
    {
        $data = self::where('id', $id);
        if ($partnerId) {
            $tableLinkStore = (new FegguLinkStore)->getTable();
            $tableLink = (new FegguLink)->getTable();
            $data = $data->leftJoin($tableLinkStore, $tableLinkStore . '.link_id', $tableLink . '.id');
            $data = $data->where($tableLinkStore . '.partner_id', $partnerId);
        }
        $data = $data->first();
        return $data;
    }

    /**
     * Get list link in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getLinkListAdmin($partnerId = null)
    {
        $linkList = (new AdminLink);
        $tableLink = $linkList->getTable();
        if ($partnerId) {
            $tableLinkStore = (new FegguLinkStore)->getTable();
            $linkList = $linkList->leftJoin($tableLinkStore, $tableLinkStore . '.link_id', $tableLink . '.id');
            $linkList = $linkList->where($tableLinkStore . '.partner_id', $partnerId);
        }
        $linkList = $linkList->orderBy($tableLink.'.id', 'desc');

        $linkList = $linkList->paginate(20);

        return $linkList;
    }

    /**
     * Create a new link
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createLinkAdmin(array $dataInsert)
    {
        $dataInsert = au_clean($dataInsert);
        return self::create($dataInsert);
    }
}
