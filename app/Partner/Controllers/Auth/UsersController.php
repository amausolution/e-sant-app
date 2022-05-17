<?php
namespace App\Partner\Controllers\Auth;

use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\PartnerPermission;
use Feggu\Core\Partner\Models\PartnerRole;
use Feggu\Core\Partner\Models\PartnerUser;

class UsersController extends RootPartnerController
{
    public $permissions;
    public $roles;
    public function __construct()
    {
        parent::__construct();
        $this->permissions = PartnerPermission::pluck('name', 'id')->all();
        $this->roles       = PartnerRole::pluck('name', 'id')->all();
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $user = AdminUser::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name'     => 'required|string|max:100',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.AdminUser::class.'",username,' . $user->id . '|string|max:100|min:3',
            'avatar'   => 'nullable|string|max:255',
            'password' => 'nullable|string|max:60|min:6|confirmed',
            'email'    => 'required|string|email|max:255|unique:"'.AdminUser::class.'",email,' . $user->id,
        ], [
            'username.regex' => au_language_render('admin.user.username_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        $dataUpdate = [
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'avatar' => $data['avatar'],
            'email' => strtolower($data['email']),
        ];
        if ($data['password']) {
            $dataUpdate['password'] = bcrypt($data['password']);
        }
        AdminUser::updateInfo($dataUpdate, $id);

        if (!in_array($user->id, AU_GUARD_ADMIN)) {
            $roles = $data['roles'] ?? [];
            $permission = $data['permission'] ?? [];
            $user->roles()->detach();
            $user->permissions()->detach();
            //Insert roles
            if ($roles) {
                $user->roles()->attach($roles);
            }
            //Insert permission
            if ($permission) {
                $user->permissions()->attach($permission);
            }
        }

//
        return redirect()->route('admin_user.index')->with('success', au_language_render('action.edit_success'));
    }

    /*
    Delete list Item
    Need mothod destroy to boot deleting in model
     */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => au_language_render('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            $arrID = array_diff($arrID, AU_GUARD_ADMIN);
            AdminUser::destroy($arrID);
            return response()->json(['error' => 1, 'msg' => '']);
        }
    }

}
