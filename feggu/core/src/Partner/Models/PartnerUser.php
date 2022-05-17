<?php
namespace Feggu\Core\Partner\Models;

use App\Partner\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PartnerUser extends Authenticatable
{
    use  Notifiable, HasApiTokens,SoftDeletes,UsesTenantConnection;
    public $table      = AU_DB_PREFIX.'partner_user';
    protected $guarded = [];
    protected $hidden  = [
        'password', 'remember_token',
    ];
    
    protected static $allPermissions = null;
    protected static $allViewPermissions = null;
    protected static $canChangeConfig = null;
    protected static $listPartnerId = null;
    protected static $listPartner = null;
    protected $appends = ['name'];
    protected $casts = [
      'timetable'=>'array',
    ];
    public function job()
    {
      return $this->belongsTo(FegguProfession::class,'profession','id');
    }

    public function appointments()
    {
       return $this->hasMany(Appointment::class,'user_id','id');
    }
    /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(PartnerRole::class, AU_DB_PREFIX.'partner_role_user', 'user_id', 'role_id');
    }

    /**
     * A user has and belongs to many patient.
     *
     * @return BelongsToMany
     */
    public function patients()
    {
        return $this->belongsToMany(FegguConsultation::class, AU_DB_PREFIX.'patient_doctor', 'doctor_id', 'patient_id');
    }

    /**
     * A user has and belongs to many hospital.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner()
    {
        return $this->hasOne(FegguPartner::class, 'partner','id');
    }

    public function specializations()
    {
        return $this->belongsToMany(FegguSpecialization::class, AU_DB_PREFIX.'feggu_speciality_doctor', 'doctor_id', 'speciality_id');
    }

    public function areDepartments()
    {
        return $this->belongsToMany(DepartmentPartner::class,DepartmentDoctor::class,'doctor_id','department_partner_id');
    }

    /**
     * A User has and belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(PartnerPermission::class, AU_DB_PREFIX.'partner_user_permission', 'user_id', 'permission_id');
    }

    /**
     * Update info customer
     * @param  [array] $dataUpdate
     * @param  [int] $id
     */
    public static function updateInfo($dataUpdate, $id)
    {
        $dataUpdate = au_clean($dataUpdate);
        $obj        = self::find($id);
        return $obj->update($dataUpdate);
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if (in_array($model->id, AU_GUARD_PARTNER)) {
                return false;
            }
            $model->roles()->detach();
            $model->permissions()->detach();
        });
        static::creating(function ($model) {
            $model->partner_id = session('partnerId');
        });
    }


    /**
     * Create new customer
     * @return [type] [description]
     */
    public static function createUser($dataInsert)
    {
        $dataUpdate = au_clean($dataInsert);
        return self::create($dataUpdate);
    }

    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public static function allPermissions()
    {
        if (self::$allPermissions === null) {
            $user                 = \Partner::user();
            self::$allPermissions = $user->roles()->with('permissions')
                ->get()->pluck('permissions')->flatten()
                ->merge($user->permissions);
        }
        return self::$allPermissions;
    }

    /**
     * Get all view permissions of user.
     *
     * @return mixed
     */
    protected static function allViewPermissions()
    {
        if (self::$allViewPermissions === null) {
            $arrView = [];
            $allPermissionTmp = self::allPermissions();
            $allPermissionTmp = $allPermissionTmp->pluck('http_uri')->toArray();
            if ($allPermissionTmp) {
                foreach ($allPermissionTmp as  $actionList) {
                    foreach (explode(',', $actionList) as  $action) {
                        if (strpos($action, 'ANY::') === 0 || strpos($action, 'GET::') === 0) {
                            $arrPrefix = ['ANY::', 'GET::'];
                            $arrScheme = ['https://', 'http://'];
                            $arrView[] = str_replace($arrScheme, '', url(str_replace($arrPrefix, '', $action)));
                        }
                    }
                }
            }
            self::$allViewPermissions = $arrView;
        }
        return self::$allViewPermissions;
    }

    /**
     * Check url menu can display
     *
     * @param   [type]  $url  [$url description]
     *
     * @return  [type]        [return description]
     */
    public function checkUrlAllowAccess($url)
    {
        if ($this->isAdministrator() || $this->isViewAll()) {
            return true;
        }
        $listUrlAllowAccess = self::allViewPermissions();
        $arrScheme = ['https://', 'http://'];
        $pathCheck = strtolower(str_replace($arrScheme, '', $url));
        if ($listUrlAllowAccess) {
            foreach ($listUrlAllowAccess as  $pathAllow) {
                if ($pathCheck === $pathAllow
                    || $pathCheck  === $pathAllow.'/'
                    || (Str::endsWith($pathAllow, '*') && ($pathCheck === str_replace('/*', '', $pathAllow) || strpos($pathCheck, str_replace('*', '', $pathAllow)) === 0))
                    || (Str::endsWith($pathAllow, '{id}') && ($pathCheck === str_replace('/{id}', '', $pathAllow) || strpos($pathCheck, str_replace('{id}', '', $pathAllow)) === 0))
                    ) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Check if user has permission.
     *
     * @param $ability
     * @param array $arguments
     *
     * @return bool
     */
    public function can($ability, $arguments = []): bool
    {
        if ($this->isAdministrator()) {
            return true;
        }

        if ($this->permissions->pluck('slug')->contains($ability)) {
            return true;
        }

        return $this->roles->pluck('permissions')->flatten()->pluck('slug')->contains($ability);
    }

    /**
     * Check if user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot($permission, $arguments = []): bool
    {
        return !$this->can($permission);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator(): bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is view_all.
     *
     * @return mixed
     */
    public function isViewAll(): bool
    {
        return $this->isRole('view.all');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole(string $role): bool
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * Check user can change config value
     *
     * @return  [type]  [return description]
     */
    public static function checkPermissionConfig()
    {
        if (self::$canChangeConfig === null) {
            if (\Partner::user()->isAdministrator()) {
                return self::$canChangeConfig = true;
            }

            if (self::allPermissions()->first(function ($permission) {
                if (!$permission->http_uri) {
                    return false;
                }
                $actions = explode(',', $permission->http_uri);
                foreach ($actions as $key => $action) {
                    $method = explode('::', $action);
                    if (
                        in_array($method[0], ['ANY', 'POST'])
                        && (
                            AU_PARTNER_PREFIX . '/config/*' == $method[1]
                        || AU_PARTNER_PREFIX . '/config/update_info' == $method[1]
                        || AU_PARTNER_PREFIX . '/config' == $method[1]
                        )
                    ) {
                        return true;
                    }
                }
            })) {
                return self::$canChangeConfig = true;
            } else {
                return self::$canChangeConfig = false;
            }
        } else {
            return self::$canChangeConfig;
        }
    }

    public function getAvatar()
    {
      return au_image_get_path($this->avatar);
    }
    /*
    Full name
    */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;

    }
    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%');
            });
        })->when($filters['identifier'] ?? null, function ($query, $identifier) {
            $query->where(function ($query) use ($identifier) {
            $query->where('matricule', '=', $identifier);
            });
        })->when($filters['job'] ?? null, function ($query, $job) {
            $query->where(function ($query) use ($job) {
                $query->where('profession', '=', $job);
            });
        });
    }

}
