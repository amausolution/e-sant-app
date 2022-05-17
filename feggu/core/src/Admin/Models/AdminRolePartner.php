<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\PartnerPermission;
use Feggu\Core\Partner\Models\PartnerUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminRolePartner extends Model
{
    protected $fillable = ['name', 'slug'];
    public $table       = AU_DB_PREFIX.'partner_role';

    public function administrators()
    {
        return $this->belongsToMany(PartnerUser::class, AU_DB_PREFIX.'partner_role_user', 'role_id', 'user_id');
    }

    /**
     * A role belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(PartnerPermission::class, AU_DB_PREFIX.'partner_role_permission', 'role_id', 'permission_id');
    }

    /**
     * A role belongs to many menus.
     *
     * @return BelongsToMany
     */
    public function menus()
    {
        return $this->belongsToMany(AdminMenuPartner::class, AU_DB_PREFIX.'partner_role_menu', 'role_id', 'menu_id');
    }

    /**
     * Check user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot(string $permission): bool
    {
        return !$this->can($permission);
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
            $model->administrators()->detach();
            $model->menus()->detach();
            $model->permissions()->detach();
        });
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
     * Create new role
     * @return [type] [description]
     */
    public static function createRole($dataInsert)
    {
        $dataUpdate = au_clean($dataInsert);
        return self::create($dataUpdate);
    }
}
