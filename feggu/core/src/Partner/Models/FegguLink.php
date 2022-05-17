<?php
#Feggu/Core/Partner/Models/FegguLink.php
namespace Feggu\Core\Partner\Models;

use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Model;

class FegguLink extends Model
{
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'feggu_link';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;
    protected static $getGroup = null;

    public function stores()
    {
        return $this->belongsToMany(FegguPartner::class, FegguLinkPartner::class, 'link_id', 'partner_id');
    }

    public static function getGroup()
    {
        if (!self::$getGroup) {
            $tableLink = (new FegguLink)->getTable();

            $dataSelect = $tableLink.'.*';
            $links = self::selectRaw($dataSelect)
                ->where($tableLink.'.status', 1);
            $partnerId = config('app.partnerId');
            if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
                $tableLinkStore = (new FegguLinkPartner())->getTable();
                $tableStore = (new FegguPartner)->getTable();
                $links = $links->join($tableLinkStore, $tableLinkStore.'.link_id', $tableLink . '.id');
                $links = $links->join($tableStore, $tableStore . '.id', $tableLinkStore.'.partner_id');
                $links = $links->where($tableStore . '.status', '1');
                $links = $links->where($tableLinkStore.'.partner_id', $partnerId);
            }

            $links = $links
                ->orderBy($tableLink.'.sort', 'asc')
                ->orderBy($tableLink.'.id', 'desc')
                ->get()
                ->groupBy('group');
            self::$getGroup = $links;
        }
        return self::$getGroup;
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($link) {
                $link->stores()->detach();
            }
        );
    }
}
