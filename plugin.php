<?php
/**
 * Claim plugin
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Claim;

use Illuminate\Database\Schema\Blueprint;
use Route;
use Schema;
use XeToggleMenu;
use Xpressengine\Plugin\AbstractPlugin;

/**
 * Claim plugin
 *
 * @category    Claim
 * @package     Claim
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

        $app = app();
        $app['xe.claim.handler'] = $app->share(
            function ($app) {
                $handler = new Handler(app('xe.config'));
                return $handler;
            }
        );
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
     * @return boolean
     */
    public function checkUpdated($installedVersion = NULL)
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

        return true;
    }

    public function update($installedVersion = null)
    {
        $this->putLang();
        $this->setToggleMenuConfig();
    }

    public function install()
    {
        $this->createClaimLogTable();
        $this->putLang();
        $this->setToggleMenuConfig();
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

    public function createClaimLogTable()
    {
        if (Schema::hasTable('claim_logs') === false) {
            Schema::create('claim_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('claimType', 36);
                $table->string('shortCut', 255);
                $table->string('targetId', 36);
                $table->string('userId', 36);
                $table->string('ipaddress', 16);
                $table->string('message', 255);
                $table->timestamp('createdAt');
                $table->timestamp('updatedAt');

                $table->index(['targetId', 'userId']);
                $table->index(['targetId', 'ClaimType']);
            });
        }
    }

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
            Route::get(
                '/',
                [
                    'as' => 'manage.claim.claim.index',
                    'uses' => 'ManagerController@index',
                    'settings_menu' => 'contents.claim'
                ]
            );
            Route::post('delete', ['as' => 'manage.claim.claim.delete', 'uses' => 'ManagerController@delete']);
            Route::get('config', ['as' => 'manage.claim.claim.config', 'uses' => 'ManagerController@config']);
            Route::get(
                'config/edit',
                ['as' => 'manage.claim.claim.config.edit', 'uses' => 'ManagerController@configEdit']
            );
            Route::post(
                'config/update',
                ['as' => 'manage.claim.claim.config.update', 'uses' => 'ManagerController@configUpdate']
            );
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
            Route::get('', ['as' => 'fixed.claim.index', 'uses' => 'UserController@index']);
            Route::post('store', ['as' => 'fixed.claim.store', 'uses' => 'UserController@store']);
            Route::post('destroy', ['as' => 'fixed.claim.destroy', 'uses' => 'UserController@destroy']);
        }, ['namespace' => 'Xpressengine\Plugins\Claim\Controllers']);
    }

    /**
     * register interception
     *
     * @return void
     */
    public static function registerSettingsMenu()
    {
        // settings menu 등록
        $menus = [
            'contents.claim' => [
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
