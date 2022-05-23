<?php
namespace Feggu\Core\Partner\Models;

use App\Partner\Models\Setting;
use App\Partner\Models\SettingPartner;
use Feggu\Core\Admin\Models\AdminConfig;
use Illuminate\Database\Eloquent\Model;

class FegguPartner extends Model
{
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'admin_partner';
    protected $guarded = [];
    protected static $getAll = null;
    protected static $getPartnerActive = null;
    protected static $getCodeActive = null;
    protected static $getDomainPartnerFeggu = null;
    protected static $getDomainPartner = null;
    protected static $getListAllActive = null;
    protected static $arrayPartnerId = null;
    protected static $listPartnerId = null;
    protected static $listPartnerCode = null;
    protected static $getPartnerDomainByCode = null;
    protected $connection = AU_CONNECTION;



    public function setting()
    {
        return $this->hasOne(Setting::class,'partner_id','id');
    }
    public function laboratory()
    {
        return $this->hasOne(PartnerLab::class,'partner_id','id');
    }

    public function part()
    {
        return $this->belongsTo(PartnerUser::class,'partner','id');
    }

    public function descriptions()
    {
        return $this->hasMany(FegguPartnerDescription::class, 'partner_id', 'id');
    }

    public function banners()
    {
        return $this->belongsToMany(FegguPartner::class, FegguBannerPartner::class, 'partner_id', 'banner_id');
    }

    public function users()
    {
       return $this->hasMany(PartnerUser::class, 'partner_id','id');
    }

    public function doctors()
    {
        return $this->users()->where('group',1)->get();
    }

    public function consultations()
    {
        return $this->hasMany(PartnerConsultation::class, 'hospital_id','id');
    }

    public function insurers()
    {
        return $this->hasMany(PartnerInsurer::class, 'hospital_id','id');
    }
    public function rooms()
    {
        return $this->hasMany(HospitalRoom::class, 'hospital_id','id');
    }

    public function patients()
    {
        return $this->belongsToMany(FegguUser::class, FegguPatient::class,'hospital_id','patient_id');
    }


    public function departments()
    {
        return $this->hasMany(DepartmentPartner::class, 'partner_id','id');
    }



    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($partner) {
            //Partner id 1 is default
            if ($partner->id == AU_ID_ROOT) {
                return false;
            }
            //Delete store descrition
            $partner->descriptions()->delete();
            $partner->news()->detach();
            $partner->banners()->detach();
            $partner->pages()->detach();
            $partner->links()->detach();
            AdminConfig::where('partner_id', $partner->id)->delete();
        });
    }


    /**
     * [getAll description]
     *
     * @return  [type]  [return description]
     */
    public static function getListAll()
    {
        if (self::$getAll === null) {
            self::$getAll = self::with('descriptions')
                ->get()
                ->keyBy('id');
        }
        return self::$getAll;
    }

    /**
     * [getAll active description]
     *
     * @return  [type]  [return description]
     */
    public static function getListAllActive()
    {
        if (self::$getListAllActive === null) {
            self::$getListAllActive = self::with('descriptions')
                ->where('active', 1)
                ->get()
                ->keyBy('id');
        }
        return self::$getListAllActive;
    }


    /**
     * Get all domain and id store is vendor unlock domain
     *
     * @return  [array]  [return description]
     */
    public static function getDomainPartner()
    {
        if (self::$getDomainPartner === null) {
            self::$getDomainPartner = self::where('partner', 1)
                ->whereNotNull('domain')
                ->where('status', 1)
                ->pluck('domain', 'id')
                ->all();
        }
        return self::$getDomainPartner;
    }


    /**
     * Get all domain and id store unlock domain
     *
     * @return  [array]  [return description]
     */
    public static function getDomainPartnerFeggu()
    {
        if (self::$getDomainPartnerFeggu === null) {
            self::$getDomainPartnerFeggu = self::whereNotNull('domain')
                ->where('status', 1)
                ->pluck('domain', 'id')
                ->all();
        }
        return self::$getDomainPartner;
    }

    /**
     * Get all domain and id store active
     *
     * @return  [array]  [return description]
     */
    public static function getPartnerActive()
    {
        if (self::$getPartnerActive === null) {
            self::$getPartnerActive = self::where('active', 1)
                ->pluck('domain', 'id')
                ->all();
        }
        return self::$getPartnerActive;
    }


    /**
     * Get all code and id store active
     *
     * @return  [array]  [return description]
     */
    public static function getCodeActive()
    {
        if (self::$getCodeActive === null) {
            self::$getCodeActive = self::where('active', 1)
                ->pluck('code', 'id')
                ->all();
        }
        return self::$getCodeActive;
    }

    /**
     * Get array store ID
     *
     * @return array
     */
    public static function getArrayPartnerId()
    {
        if (self::$arrayPartnerId === null) {
            self::$arrayPartnerId = self::pluck('id')->all();
        }
        return self::$arrayPartnerId;
    }

    //Function get text description
    public function getText()
    {
        return $this->descriptions()->where('lang', au_get_locale())->first();
    }
    public function getTitle()
    {
        return $this->getText()->title ?? '';
    }
    public function getDescription()
    {
        return $this->getText()->description ?? '';
    }
    public function getKeyword()
    {
        return $this->getText()->keyword?? '';
    }
    //End  get text description


    //===========Get infor store======
    /**
     * Get list store ID
     */
    public static function getListPartnerId()
    {
        if (self::$listPartnerId === null) {
            self::$listPartnerId = self::pluck('id', 'code')->all();
        }
        return self::$listPartnerId;
    }

    /**
     * Get list store code
     */
    public static function getListPartnerCode()
    {
        if (self::$listPartnerCode === null) {
            self::$listPartnerCode = self::pluck('code', 'id')->all();
        }
        return self::$listPartnerCode;
    }

    /**
     * Get all domain and code store active
     *
     * @return  [array]  [return description]
     */
    public static function getPartnerDomainByCode()
    {
        if (self::$getPartnerDomainByCode === null) {
            self::$getPartnerDomainByCode = self::whereNotNull('domain')
                ->pluck('domain', 'code')
                ->all();
        }
        return self::$getPartnerDomainByCode;
    }


    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

}
