<?php

namespace Feggu\Core\Partner\Controllers\Auth;

use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\PartnerPermission;
use Feggu\Core\Partner\Models\PartnerRole;
use Feggu\Core\Partner\Partner;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Symfony\Component\Translation\TranslatorInterface;

class LoginPartnerController extends RootPartnerController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Show the login page.
     *
     * @return \Inertia\Response
     */
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return Inertia::render('Partner/Auth/Login');
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $this->loginValidator($request->all())->validate();

        $credentials = $request->only([$this->username(), 'password']);
        $remember = $request->get('remember', false);

        if ($this->guard()->attempt($credentials, $remember)) {
            return $this->sendLoginResponse($request);
        }
        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
    {
        return Validator::make($data, [
            $this->username() => 'required',
            'password' => 'required',
        ]);
    }

    /**
     * User logout.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function getLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(AU_PARTNER_PREFIX);
    }

    public function getSetting()
    {
        $user = Partner::user();
        if ($user === null) {
            return 'no data';
        }
        $data = [
            'title' => au_language_render('admin.setting_account'),
            'subTitle' => '',
            'title_description' => '',
            'icon' => 'fa fa-edit',
            'user' => $user,
            'roles' => (new PartnerRole)->pluck('name', 'id')->all(),
            'permission' => (new PartnerPermission)->pluck('name', 'id')->all(),
            'url_action' => au_route_partner('partner.setting'),
        ];
        return view($this->templatePathPartner.'auth.setting')
            ->with($data);
    }

    public function putSetting()
    {
        $user = Partner::user();
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name' => 'required|string|max:100',
            'avatar' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:60|min:6|confirmed',
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
            'avatar' => $data['avatar'],
        ];
        if ($data['password']) {
            $dataUpdate['password'] = bcrypt($data['password']);
        }
        $user->update($dataUpdate);
//
        return redirect()->route('partner.home')->with('success', au_language_render('action.edit_success'));
    }

    /**
     * @return string|TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return lang::has('auth.failed')
        ? __('Auth ailed')
        : 'These credentials do not match our records.';
    }

    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : AU_PARTNER_PREFIX;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param Request $request
     *
     * @return Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath())->with(['success' => au_language_render('partner.login_successful')]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('partner');
    }
}
