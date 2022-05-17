<?php
#Feggu/Core/Partner/Models/FegguPartnerBlockContent.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Feggu\Core\Partner\Models\FegguPartner;
class FegguPartnerBlockContent extends Model
{
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'feggu_partner_block';
    protected $guarded = [];
    private static $getLayout = null;
    protected $connection = AU_CONNECTION;

    public static function getLayout()
    {
        if (self::$getLayout === null) {
            $store = FegguPartner::find(config('app.partnerId'));
            $template = '';
            if ($store) {
                $template = $store->template;
            }
            self::$getLayout = self::where('status', 1)
                ->where('partner_id', config('app.partnerId'))
                ->where('template', $template)
                ->orderBy('sort', 'asc')
                ->get()
                ->groupBy('position');
        }
        return self::$getLayout;
    }
}
