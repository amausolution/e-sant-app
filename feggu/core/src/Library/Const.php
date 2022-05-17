<?php

// list ID admin guard
define('AU_GUARD_ADMIN', ['1']); // admin
define('AU_GUARD_PARTNER', ['1']); // partner
// list ID language guard
define('AU_GUARD_LANGUAGE', ['1', '2']); // fr, en
// list ID currency guard
define('AU_GUARD_CURRENCY', ['1', '2']); // fcfa , usd
// list ID ROLES guard
define('AU_GUARD_ROLES', ['1', '2']); // admin, only view

/**
 * Admin define
 */
define('AU_ADMIN_MIDDLEWARE', ['web', 'admin']);

define('AU_PARTNER_MIDDLEWARE', ['web', 'partner']);
define('AU_API_MIDDLEWARE', ['api', 'api.extend']);
define('AU_CONNECTION', 'mysql');
define('AU_CONNECTION_LOG', 'mysql');
//Prefix url admin
define('AU_ADMIN_PREFIX', config('const.ADMIN_PREFIX'));
define('AU_PARTNER_PREFIX', config('const.PARTNER_PREFIX'));
//Prefix database
define('AU_DB_PREFIX', config('const.DB_PREFIX'));
// Root ID store
define('AU_ID_ROOT', 1);
define('AU_LAB_IN_SIDE', 1);
define('AU_LAB_EX', 2);
define('BASE_PASSWORD','f0330909-1e42-4771-a0a3-25077d9517f1');
