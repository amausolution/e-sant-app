<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'layouts.app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // Lazily
            'auth.user' => fn () => $request->user('partner')
                ? $request->user('partner')->only('id', 'name','first_name','last_name' ,'email','username','mobil')
                : null,
            'auth.partner' => fn () => getPartner()->only('type','title')?
                : null,
            //roles for user
            'auth.roles'=> fn () => $request->user('partner')
                ? $request->user('partner')->roles()->get()->map->only('id','name')
                : [],
            //permissions for user
            'auth.permissions'=> fn () => $request->user('partner')
                ? $request->user('partner')->permissions()->get()->map->only('id','name')
                : [],

            'session'=> fn () => session('partnerId') ?: 1,
            'logo'=> fn () => getPartner()->logo ? asset(getPartner()->logo):null,
            'favicon'=> fn () => getPartner()->logo ? asset(getPartner()->logo):null,
            'avatar'=> fn () => $request->user('partner')? asset($request->user('partner')->getAvatar()):null,
            'config'=> fn ()=> au_partner_config()??[],
            'local'=>session('local'),
            'notify' => [
                'message' => fn () => $request->session()->get('message')
            ],
            'appName' => au_partner('title'),
            // 'partnerUser'=>\Partner::user() ?? null,
            'departmentDoctor'=> showDepart(session('departmentIds')),
            'room'=> session('room'),
            'statusConsultation'=> statusPatient(),
            'logoLoging'=>au_file(au_partner('logo')),
        ]);
    }
}
