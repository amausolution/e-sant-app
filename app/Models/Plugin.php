<?php


namespace App\Models;


use Feggu\Core\Partner\Models\PartnerConfig;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Plugin extends ConfigDefault
{

    public function __construct($key)
    {
        $this->partnerId = session('partnerId');
        $this->configKey = $key;

    }

    public function uninstall()
    {
        if (Schema::hasTable($this->table)) {
            Schema::drop($this->table);
        }

        if (Schema::hasTable(AU_DB_PREFIX.'type_analyse')) {
            Schema::drop(AU_DB_PREFIX.'type_analyse');
        }
    }

    public function install()
    {
        $this->uninstall();

        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('partner')->index();
            $table->string('phone', 16);
            $table->string('mobile', 16);
            $table->string('email', 200)->nullable();
            $table->integer('store_id')->default(1)->index();
            $table->tinyInteger('type');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create(AU_DB_PREFIX.'type_analyse', function (Blueprint $table) {
            $table->id();
            $table->uuid('laboratory_id');
            $table->json('analyse');
            $table->integer('price');
            $table->string('duration')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('take_assurance')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function enable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new PartnerConfig)->where('partner_id',$this->partnerId)->where('key', $this->configKey)->update(['value' => self::ON]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error enable'];
        }
        return $return;
    }

    public function disable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new PartnerConfig)->where('partner_id',$this->partnerId)->where('key', $this->configKey)->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => __('plugin.plugin_action.action_error', ['action' => 'Disable'])];
        }
        return $return;
    }

}
