<?php

namespace Feggu\Core\Api\Controllers;

use App\Http\Controllers\RootFrontController;
use Feggu\Core\Partner\Models\FegguUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Feggu\Core\Partner\Models\FegguConsultation;
use Illuminate\Support\Facades\Validator;
use Feggu\Core\Partner\Models\FegguEmailTemplate;
use Feggu\Core\Partner\Controllers\Auth\AuthTrait;

class PatientAuthController extends RootFrontController
{
    use AuthTrait;

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['login', 'password']);

        if (!$this->guard()->attempt($credentials)) {
            return response()->json([
                'error' => 1,
                'msg' => 'Unauthorized'
            ], 401);
        }

        $user = $this->guard()->user();

        if ($user->status == 0) {
            $scope = ['user-guest'];
        } else {
            $scope = ['user'];
        }

        $tokenResult = $user->createToken('Client:'.$user->email.'- '.now(), $scope);
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Create new customer
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $data['country'] = strtoupper($data['country'] ?? '');
        $v = $this->validator($data);
        if ($v->fails()) {
            $msg = '';
            foreach ($v->errors()->toArray() as $key => $value) {
                $msg .=$key." : ".$value[0]."\n ";
            }
            return response()->json([
                'error' => 1,
                'msg' => 'Error while create new customer',
                'detail' => $msg
            ]);
        }
        $user = $this->insert($data);
        return response()->json($user);
    }

    /**
     * Validate data input
     */
    protected function validator(array $data)
    {
        $dataMapping = $this->mappingValidator($data);
        return Validator::make($data, $dataMapping['validate'], $dataMapping['messages']);
    }

    /**
     * Inser data new customer
     */
    protected function insert($data)
    {
        $dataMap = $this->mappDataInsert($data);

        $user = FegguUser::createCustomer($dataMap);
        if ($user) {
            if (au_config('welcome_customer')) {
                $checkContent = (new FegguEmailTemplate)->where('group', 'welcome_customer')->where('status', 1)->first();
                if ($checkContent) {
                    $content = $checkContent->text;
                    $dataFind = [
                        '/\{\{\$title\}\}/',
                        '/\{\{\$first_name\}\}/',
                        '/\{\{\$last_name\}\}/',
                        '/\{\{\$email\}\}/',
                        '/\{\{\$phone\}\}/',
                        '/\{\{\$password\}\}/',
                        '/\{\{\$locality\}\}/',
                        '/\{\{\$address\}\}/',
                        '/\{\{\$detail_address\}\}/',
                        '/\{\{\$country\}\}/',
                    ];
                    $dataReplace = [
                        au_language_render('email.welcome'),
                        $dataMap['first_name'],
                        $dataMap['last_name'],
                        $dataMap['email'],
                        $dataMap['phone'],
                        $dataMap['password'],
                        $dataMap['locality'],
                        $dataMap['address'],
                        $dataMap['detail_address'],
                        $dataMap['country'],
                    ];
                    $content = preg_replace($dataFind, $dataReplace, $content);
                    $dataView = [
                        'content' => $content,
                    ];

                    $config = [
                        'to' => $data['email'],
                        'subject' => au_language_render('email.welcome'),
                    ];

                    au_send_mail($this->templatePath . '.mail.welcome_customer', $dataView, $config, []);
                }
            }
        }
        return $user;
    }


    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'error' => 0,
            'msg' => 'Successfully logged out'
        ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
