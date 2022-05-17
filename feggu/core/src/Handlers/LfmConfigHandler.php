<?php

namespace Feggu\Core\Handlers;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
        // If domain is root, dont split folder
        if (session('adminPartnerId') == AU_ID_ROOT) {
            return ;
        }

        if (au_config_global('MultiPartnerPro')) {
            return session('adminPartnerId');
        } else {
            return;
        }
    }
}
