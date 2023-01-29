<?php

namespace Xpressengine\Plugins\Claim\Migrations\Table;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Xpressengine\Support\Migration;
use Xpressengine\Plugins\Claim\Models\ClaimLog;

/**
 * Class ClaimLogTable
 * @package Xpressengine\Plugins\Claim\Migrations\Table
 */
class ClaimLogTable extends Migration
{
    /**
     * @return void
     */
    public function install()
    {
        if ($this->checkInstalled() === false) {
            $this->createTable();
        }
    }

    /**
     * @return bool
     */
    public function checkInstalled()
    {
        return Schema::hasTable(ClaimLog::TABLE_NAME);
    }

    /**
     * @return void
     */
    protected function createTable()
    {
        Schema::create(ClaimLog::TABLE_NAME, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('claim_type', 36);
            $table->string('short_cut', 255);
            $table->string('target_id', 36);
            $table->string('user_id', 36);
            $table->string('ipaddress', 16);
            $table->string('message', 255);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->index(['target_id', 'user_id']);
            $table->index(['target_id', 'claim_type']);
        });
    }

    /**
     * @param $installedVersion
     * @return bool
     */
    public function checkUpdated($installedVersion = null)
    {
        return true;
    }

    /**
     * @param $installedVersion
     * @return void
     */
    public function update($installedVersion = null)
    {

    }
}
