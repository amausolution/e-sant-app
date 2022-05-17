<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
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
        $dataPartner = [];
        if (\Partner::user()){
            $user = \Partner::user();
            $dataPartner = [
                'session'=> session('partnerId'),
                'logo'=> asset(getPartner()->logo),
                'title_partner'=>getPartner()->getTitle() ?? '',
            ];
            $partner = [
                'avatar'=> asset($user->getAvatar()),
                'name'=> $user->name,
                'username'=>$user->username,
                'mobil'=>$user->phone,
            ];
            }
        return array_merge(parent::share($request), [
            'notify' => [
                'message' => fn () => $request->session()->get('message')
            ],
            'getPartner'=>$dataPartner,
            'partner'=>$partner ?? null,
            'appName' => au_partner('title'),
            'partnerUser'=>\Partner::user() ?? null,
            'departmentDoctor'=> showDepart(session('departmentIds')),
            'room'=> session('room'),
            'statusConsultation'=> statusPatient(),
            'logoLoging'=>au_file(au_partner('logo')),
            'asset'=>asset('/')
        ]);
    }
}
