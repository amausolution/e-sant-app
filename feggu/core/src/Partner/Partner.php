<?php

namespace Feggu\Core\Partner;

use Feggu\Core\Partner\Models\AdminMenuPartner;
use Feggu\Core\Partner\Models\PartnerMenu;
use Illuminate\Support\Facades\Auth;

/**
 * Class Partner.
 */
class Partner
{
    public static function user()
    {
        return Auth::guard('partner')->user();
    }

    public static function isLoginPage()
    {
        return (request()->route()->getName() == 'partner.login');
    }

    public static function isLogoutPage()
    {
        return (request()->route()->getName() == 'partner.logout');
    }
    public static function getMenu()
    {
        return PartnerMenu::getListAll()->groupBy('parent_id');
    }
    public static function getMenuVisible()
    {
        return PartnerMenu::getListVisible();
    }
    public static function checkUrlIsChild($urlParent, $urlChild)
    {
        return PartnerMenu::checkUrlIsChild($urlParent, $urlChild);
    }
    public static function getMenuPartner()
    {
        return AdminMenuPartner::getListAll()->groupBy('parent_id');
    }
}
