<?php
#App\Plugins\ConfigDefault.php
namespace App\Models;

abstract class  ConfigDefault
{
    public $configGroup;
    public $configCode;
    public $configKey;
    public $partnerId;
    const ON = 1;
    const OFF = 0;
    const ALLOW = 1;
    const DENIED = 0;

    /**
     * Install app
     */
    abstract public function install();

    /**
     * Uninstall app
     */
    abstract public function uninstall();

    /**
     * Enable app
     */
    abstract public function enable();

    /**
     * Disable app
     */
    abstract public function disable();


    /**
     * Config app
     */
    public function config()
    {
        return null;
    }

    /**
     * Config app
     */
    public function endApp()
    {
        return null;
    }
}
