<?php

namespace Feggu\Core;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Feggu\Core\Partner\Models\FegguBanner;
use Feggu\Core\Partner\Models\FegguBrand;
use Feggu\Core\Partner\Models\FegguNews;
use Feggu\Core\Partner\Models\FegguPage;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Commands\Customize;
use Feggu\Core\Commands\Backup;
use Feggu\Core\Commands\Restore;
use Feggu\Core\Commands\MakePlugin;
use Feggu\Core\Commands\Infomation;
use Laravel\Passport\Passport;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;

class AmaServiceProvider extends ServiceProvider
{
    protected $commands = [
        Customize::class,
        Backup::class,
        Restore::class,
        MakePlugin::class,
        Infomation::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!file_exists(public_path('install.php')) && file_exists(base_path('.env'))) {
            //Load helper from partner
            try {
                foreach (glob(__DIR__.'/Library/Helpers/*.php') as $filename) {
                    require_once $filename;
                }
            } catch (\Throwable $e) {
                $msg = '#SC001::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                // au_report($msg);
                echo $msg;
                exit;
            }

            //Load helper from vendor
            try {
                foreach (glob(app_path() . '/Library/Helpers/*.php') as $filename) {
                    require_once $filename;
                }
            } catch (\Throwable $e) {
                $msg = '#SC002::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                au_report($msg);
                echo $msg;
                exit;
            }
            //Check connection
            try {
                DB::connection(AU_CONNECTION)->getPdo();
            } catch (\Throwable $e) {
                $msg = '#SC003::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                au_report($msg);
                echo $msg;
                exit;
            }
            //Load Plugin Provider
            try {
                foreach (glob(app_path() . '/Plugins/*/*/Provider.php') as $filename) {
                    require_once $filename;
                }
            } catch (\Throwable $e) {
                $msg = '#SC004::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                au_report($msg);
                echo $msg;
                exit;
            }

            //Boot process Feggu
            try {
                $this->bootFeggu();
            } catch (\Throwable $e) {
                $msg = '#SC005::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                au_report($msg);
                echo $msg;
                exit;
            }

            //Route Admin
            try {
                if (file_exists($routes = __DIR__.'/Admin/routes.php')) {
                    $this->loadRoutesFrom($routes);
                }
            } catch (\Throwable $e) {
                $msg = '#SC006::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                au_report($msg);
                echo $msg;
                exit;
            }
            //Route Partner
            try {
                if (file_exists($routes = __DIR__ . '/Partner/routes.php')) {
                    $this->loadRoutesFrom($routes);
                }
            } catch (\Throwable $e) {
                $msg = '#SC008::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                au_report($msg);
                echo $msg;
                exit;
            }

            //Route Api
            try {
                if (au_config_global('api_mode')) {
                    if (file_exists($routes = __DIR__.'/Api/routes.php')) {
                        $this->loadRoutesFrom($routes);
                    }
                }
            } catch (\Throwable $e) {
                $msg = '#SC007::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
                au_report($msg);
                echo $msg;
                exit;
            }


        }

        try {
            $this->registerPublishing();
        } catch (\Throwable $e) {
            $msg = '#SC009::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            au_report($msg);
            echo $msg;
            exit;
        }

        try {
            $this->registerRouteMiddleware();
        } catch (\Throwable $e) {
            $msg = '#SC010::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            au_report($msg);
            echo $msg;
            exit;
        }

        try {
            $this->commands($this->commands);
        } catch (\Throwable $e) {
            $msg = '#SC011::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            au_report($msg);
            echo $msg;
            exit;
        }

        try {
            $this->validationExtend();
        } catch (\Throwable $e) {
            $msg = '#SC012::Message: ' .$e->getMessage().' - Line: '.$e->getLine().' - File: '.$e->getFile();
            au_report($msg);
            echo $msg;
            exit;
        }

        //===========Laravel Passport====================
        //https://laravel.com/docs/8.x/passport
//        Passport::routes();
//        Passport::tokensExpireIn(now()->addDays(config('passport.config.tokensExpireIn', 15)));
//        Passport::refreshTokensExpireIn(now()->addDays(config('passport.config.refreshTokensExpireIn', 30)));
//        Passport::personalAccessTokensExpireIn(now()->addMonths(config('passport.config.personalAccessTokensExpireIn', 6)));

        /**
         * Run command line passport outside console
         */
    /*    $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);

        Passport::tokensCan([
            'user' => 'User partner',
            'user-guest' => 'User guest',
            'admin' => 'Admin feggu',
            'partner' => 'Partner feggu',
            'admin-supper' => 'Admin supper',
        ]);

        Passport::setDefaultScope([
            'user-guest',
        ]);*/
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (file_exists(__DIR__.'/Library/Const.php')) {
            require_once(__DIR__.'/Library/Const.php');
        }

        $this->mergeConfigFrom(__DIR__.'/Config/admin.php', 'admin');
        $this->mergeConfigFrom(__DIR__.'/Config/partner.php', 'partner');
        $this->mergeConfigFrom(__DIR__.'/Config/validation.php', 'validation');
        $this->mergeConfigFrom(__DIR__.'/Config/lfm.php', 'lfm');
        $this->mergeConfigFrom(__DIR__.'/Config/feggu.php', 'feggu');
        $this->mergeConfigFrom(__DIR__.'/Config/middleware.php', 'middleware');
        $this->loadViewsFrom(__DIR__.'/Views/admin', 'feggu-admin');
        $this->loadViewsFrom(__DIR__.'/Views/partner', 'feggu-partner');
        $this->loadViewsFrom(__DIR__ . '/Views/front', 'feggu-front');

        //Dont use migrate from library passport
       // Passport::ignoreMigrations();
    }

    public function bootFeggu()
    {
        // Set partner id
        // Default is domain root
        $partnerId = AU_ID_ROOT;

        //Process for multi partner
        if (au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')) {
            $domain = au_process_domain_partner(url('/'));
            if (au_config_global('MultiVendorPro')) {
                $arrDomain = FegguPartner::getDomainPartner();
            }
            if (in_array($domain, $arrDomain, true)) {
                $partnerId = array_search($domain, $arrDomain, true);
            }
        }
        //End process multi partner
        config(['app.partnerId' => $partnerId]);
        // end set partner Id

        if (au_config_global('LOG_SLACK_WEBHOOK_URL')) {
            config(['logging.channels.slack.url' => au_config_global('LOG_SLACK_WEBHOOK_URL')]);
        }

        //Config language url
        config(['app.seoLang' => (au_config_global('url_seo_lang') ? '{lang?}/' : '')]);

        //Title app
        config(['app.name' => au_partner('title')]);

        //Config for  email
        if (
            // Default use smtp mode for for supplier if use multi-partner
            ($partnerId != AU_ID_ROOT && (au_config_global('MultiVendorPro') || au_config_global('MultiStorePro')))
            ||
            // Use smtp config from admin if root domain have smtp_mode enable
            ($partnerId == AU_ID_ROOT && au_config_global('smtp_mode'))
            ) {
            $smtpHost     = au_config('smtp_host');
            $smtpPort     = au_config('smtp_port');
            $smtpSecurity = au_config('smtp_security');
            $smtpUser     = au_config('smtp_user');
            $smtpPassword = au_config('smtp_password');
            $smtpName     = au_config('smtp_name');
            $smtpFrom     = au_config('smtp_from');
            config(['mail.default'                 => 'smtp']);
            config(['mail.mailers.smtp.host'       => $smtpHost]);
            config(['mail.mailers.smtp.port'       => $smtpPort]);
            config(['mail.mailers.smtp.encryption' => $smtpSecurity]);
            config(['mail.mailers.smtp.username'   => $smtpUser]);
            config(['mail.mailers.smtp.password' => $smtpPassword]);
            config(['mail.from.address' => ($smtpFrom ?? au_partner('email'))]);
            config(['mail.from.name' => ($smtpName ?? au_partner('title'))]);
        } else {
            //Set default
            config(['mail.from.address' => (config('mail.from.address')) ? config('mail.from.address'): au_partner('email')]);
            config(['mail.from.name' => (config('mail.from.name')) ? config('mail.from.name'): au_partner('title')]);
        }
        //email

        // Time zone
        config(['app.timezone' => (au_partner('timezone') ?? config('app.timezone'))]);
        // End time zone
        //share icone
        view()->share('icon_partner','');

        //Share variable for view
        view()->share('au_languages', au_language_all());
        view()->share('au_currencies', au_currency_all());
        view()->share('au_blocksContent', au_partner_block());
        view()->share('au_layoutsUrl', au_link());
        view()->share('au_templatePath', 'templates.' . au_partner('template'));
        view()->share('au_templateFile', 'templates/' . au_partner('template'));
        //variable model

        view()->share('modelBanner', (new FegguBanner));
        view()->share('modelBrand', (new FegguBrand));
        view()->share('modelNews', (new FegguNews));
        view()->share('modelPage', (new FegguPage));
        //
        view()->share('templatePathAdmin', config('admin.path_view'));
        view()->share('templatePathPartner', config('partner.path_view'));
    }

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'localization'     => Partner\Middleware\Localization::class,
        'currency'         => Partner\Middleware\Currency::class,
        'api.connection'   => Api\Middleware\ApiConnection::class,
        'checkdomain'      => Partner\Middleware\CheckDomain::class,
        'json.response'    => Api\Middleware\ForceJsonResponse::class,
        'partner.auth'       => Partner\Middleware\Authenticate::class,
        'partner.log'        => Partner\Middleware\LogOperation::class,
        'partner.permission' => Partner\Middleware\PermissionMiddleware::class,
        'partner.partnerId'    => Partner\Middleware\PartnerId::class,
        'partner.theme'      => Partner\Middleware\PartnerTheme::class,
       // 'partner.department'      => Partner\Middleware\PartnerDepartment::class,

        //Admin
        'admin.auth'       => Admin\Middleware\Authenticate::class,
        'admin.log'        => Admin\Middleware\LogOperation::class,
        'admin.permission' => Admin\Middleware\PermissionMiddleware::class,
        'admin.partnerId'    => Admin\Middleware\AdminPartnerId::class,
        'admin.theme'      => Admin\Middleware\AdminTheme::class,
        //Passport
        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected function middlewareGroups()
    {
        return [
            'admin' => config('middleware.admin'),
            'partner' => config('middleware.partner'),
            'api.extend' => config('middleware.api_extend'),
        ];
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups() as $key => $middleware) {
            app('router')->middlewareGroup($key, array_values($middleware));
        }
    }


    /**
     * Validattion extend
     *
     * @return  [type]  [return description]
     */
    protected function validationExtend()
    {
        Validator::extend('product_sku_unique', function ($attribute, $value, $parameters, $validator) {
            $productId = $parameters[0] ?? '';
            return (new Admin\Models\AdminProduct)
                ->checkProductValidationAdmin('sku', $value, $productId, session('adminPartnerId'));
        });

        Validator::extend('product_alias_unique', function ($attribute, $value, $parameters, $validator) {
            $productId = $parameters[0] ?? '';
            return (new Admin\Models\AdminProduct)
                ->checkProductValidationAdmin('alias', $value, $productId, session('adminPartnerId'));
        });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/Views/admin'  => resource_path('views/vendor/feggu-admin')], 'au:view-admin');
            $this->publishes([__DIR__.'/Views/partner'  => resource_path('views/vendor/feggu-partner')], 'au:view-admin');
            $this->publishes([__DIR__ . '/Views/front' => resource_path('views/vendor/feggu-front')], 'au:view-front');
            $this->publishes([__DIR__.'/Config/admin.php' => config_path('admin.php')], 'au:config-admin');
            $this->publishes([__DIR__.'/Config/partner.php' => config_path('partner.php')], 'au:config-partner');
            $this->publishes([__DIR__.'/Config/validation.php' => config_path('validation.php')], 'au:config-validation');
        }
    }
}
