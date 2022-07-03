<?php
/**
 * Plugin
 *
 * PHP version 7
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Claim;

use Illuminate\Database\Schema\Blueprint;
use Route;
use Schema;
use XeToggleMenu;
use Xpressengine\Plugin\AbstractPlugin;

/**
 * Plugin
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class Plugin extends AbstractPlugin
{
    /**
     * boot
     *
     * @return void
     */
    public function boot()
    {
        $this->registerToggleMenu();
        $this->registerManageRoute();
        $this->registerFixedRoute();
        $this->registerSettingsMenu();

        app()->singleton(Handler::class, function () {
            $proxyClass = app('xe.interception')->proxy(Handler::class);
            return new $proxyClass(app('xe.config'));
        });

        app()->alias(Handler::class, 'xe.claim.handler');
    }

    /**
     * activate
     *
     * @param null $installedVersion installed version
     * @return void
     */
    public function activate($installedVersion = null)
    {
    }

    /**
     * @return void
     */
    public function install()
    {
        $this->createClaimLogTable();
        $this->putLang();
        $this->setToggleMenuConfig();
    }

    /**
     * @return boolean
     */
    public function checkUpdated($installedVersion = null)
    {
        if (version_compare($installedVersion, '0.9.1', '<=')) {
            $toggleMenuId = 'module/board@board';
            $activated = XeToggleMenu::getActivated($toggleMenuId);
            $itemId = 'module/board@board/toggleMenu/claim@boardClaimItem';

            if (isset($activated[$itemId]) === false) {
                return false;
            }

            $toggleMenuId = 'comment';
            $activated = XeToggleMenu::getActivated($toggleMenuId);
            $itemId = 'comment/toggleMenu/claim@commentClaimItem';

            if (isset($activated[$itemId]) === false) {
                return false;
            }
        }

        if ($this->checkUpdatedClaimLogTable() === false) {
            return false;
        }

        return true;
    }

    /**
     * @param $installedVersion
     * @reutrn void
     */
    public function update($installedVersion = null)
    {
        $this->putLang();
        $this->setToggleMenuConfig();

        if ($this->checkUpdatedClaimLogTable() === false) {
            $this->updateClaimLogTable();
        }
    }

    /**
     * set toggle menu item to board, comment
     *
     * @return void
     */
    protected function setToggleMenuConfig()
    {
        $toggleMenuId = 'module/board@board';
        $activated = XeToggleMenu::getActivated($toggleMenuId);
        $itemId = 'module/board@board/toggleMenu/claim@boardClaimItem';

        if (isset($activated[$itemId]) === false) {
            $setActivate = array_keys($activated);
            $setActivate[] = $itemId;
            XeToggleMenu::setActivates($toggleMenuId, null, $setActivate);
        }

        $toggleMenuId = 'comment';
        $activated = XeToggleMenu::getActivated($toggleMenuId);
        $itemId = 'comment/toggleMenu/claim@commentClaimItem';

        if (isset($activated[$itemId]) === false) {
            $setActivate = array_keys($activated);
            $setActivate[] = $itemId;
            XeToggleMenu::setActivates($toggleMenuId, null, $setActivate);
        }
    }

    /**
     * Create Claim Log Table
     *
     * @reutrn void
     */
    protected function createClaimLogTable()
    {
        if (Schema::hasTable('claim_logs') === false) {
            Schema::create('claim_logs', function (Blueprint $table) {
                // claim_logs table
                $table->engine = 'InnoDB';

                // columns
                $table->bigIncrements('id');
                $table->string('claim_type', 36);
                $table->string('short_cut', 255);
                $table->string('target_id', 36);
                $table->string('user_id', 36);
                $table->string('ipaddress', 16);
                $table->string('message', 255);
                $table->string('category_item_id', 255)->nullable();
                $table->timestamps();

                // index
                $table->index('user_id');
                $table->index('category_item_id');
                $table->index(['target_id', 'user_id']);
                $table->index(['target_id', 'claim_type']);
            });

            Schema::table('claim_logs', function (Blueprint $table) {
                // foreign
                $table->foreign('user_id')->references('id')->on('user');
                $table->foreign('category_item_id')->references('id')->on('category_item');
            });
        }
    }

    /**
     * Update Claim Log Table
     *
     * @return void
     */
    protected function updateClaimLogTable()
    {
        if (Schema::hasColumn('claim_logs', 'category_item_id') === false) {
            Schema::table('claim_logs', function (Blueprint $table) {
                $table->string('category_item_id', 255)->nullable()->after('message');
            });
        }
    }

    /**
     * Check Updated Claim Log Table
     *
     * @return bool
     */
    protected function checkUpdatedClaimLogTable()
    {
        return Schema::hasColumn('claim_logs', 'category_item_id') === true;
    }

    /**
     * @return void
     */
    protected function putLang()
    {
        app('xe.translator')->putFromLangDataSource('claim', base_path('plugins/claim/langs/lang.php'));
    }

    /**
     * register toggle menu
     *
     * @return void
     */
    protected function registerToggleMenu()
    {
        app('xe.pluginRegister')->add(ToggleMenus\BoardClaimItem::class);
        app('xe.pluginRegister')->add(ToggleMenus\CommentClaimItem::class);
    }

    /**
     * Register Plugin Settings Route
     *
     * @return void
     */
    protected function registerManageRoute()
    {
        Route::settings(self::getId(), function () {
            Route::get('/', [
                'as' => 'manage.claim.claim.index',
                'uses' => 'ManagerController@index',
                'settings_menu' => 'contents.claim'
            ]);

            Route::get('config', [
                'as' => 'manage.claim.claim.config',
                'uses' => 'ManagerController@config',
                'settings_menu' => 'setting.claim'
            ]);

            Route::post('delete', [
                'as' => 'manage.claim.claim.delete',
                'uses' => 'ManagerController@delete'
            ]);

            Route::post('config/update', [
                'as' => 'manage.claim.claim.config.update',
                'uses' => 'ManagerController@updateConfig'
            ]);

            Route::post('config/storeCategory', [
                'as' => 'manage.claim.claim.config.storeCategory',
                'uses' => 'ManagerController@storeCategory'
            ]);
        }, ['namespace' => 'Xpressengine\Plugins\Claim\Controllers']);
    }

    /**
     * register fixed route
     *
     * @return void
     */
    protected function registerFixedRoute()
    {
        Route::fixed('claim', function () {
            Route::get('', [
                'as' => 'fixed.claim.index',
                'uses' => 'UserController@index'
            ]);

            Route::get('modal', [
                'as' => 'fixed.claim.modal',
                'uses' => 'UserController@modal'
            ]);

            Route::post('store', [
                'as' => 'fixed.claim.store',
                'uses' => 'UserController@store'
            ]);

            Route::post('destroy', [
                'as' => 'fixed.claim.destroy',
                'uses' => 'UserController@destroy'
            ]);
        }, ['namespace' => 'Xpressengine\Plugins\Claim\Controllers']);
    }

    /**
     * register interception
     *
     * @return void
     */
    public function registerSettingsMenu()
    {
        // settings menu 등록
        $menus = [
            'contents.claim' => [
                'title' => 'xe::claim',
                'display' => true,
                'description' => 'blur blur~',
                'ordering' => 5000
            ],
            'setting.claim' => [
                'title' => 'xe::claim',
                'display' => true,
                'description' => 'blur blur~',
                'ordering' => 5000
            ],
        ];

        foreach ($menus as $id => $menu) {
            app('xe.register')->push('settings/menu', $id, $menu);
        }
    }
}
