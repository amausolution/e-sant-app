<?php

namespace Feggu\Core\Partner\Models;

use Feggu\Core\Partner\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PartnerPermission extends Model
{
    public $table = AU_DB_PREFIX.'partner_permission';
    protected $fillable = ['name', 'slug', 'http_uri'];

    use UsesTenantConnection;
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(PartnerRole::class, PartnerPermissionUser::class, 'permission_id', 'role_id');
    }

    /**
     * If request should pass through the current permission.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function passRequest(Request $request): bool
    {
        if (empty($this->http_uri)) {
            return false;
        }

        $uriCurrent = \Route::getCurrentRoute()->uri;
        $methodCurrent = $request->method();
        $actions = explode(',', $this->http_uri);

        foreach ($actions as $key => $action) {
            $method = explode('::', $action);
            if ($method[0] === 'ANY' && ($request->path() . '/*' == $method[1] || $request->is($method[1]))) {
                return true;
            }
            if ($methodCurrent . '::' . $uriCurrent == $action) {
                return true;
            }
        }

        return false;
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
            $model->roles()->detach();
        });
    }

    /**
     * Update info
     * @param  [array] $dataUpdate
     * @param  [int] $id
     */
    public static function updateInfo($dataUpdate, $id)
    {
        $dataUpdate = $dataUpdate;
        $obj = self::find($id);
        return $obj->update($dataUpdate);
    }

    /**
     * Create new permission
     * @return [type] [description]
     */
    public static function createPermission($dataInsert)
    {
        $dataUpdate = $dataInsert;
        return self::create($dataUpdate);
    }
}
