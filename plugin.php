<?php
namespace Xpressengine\Plugins\Claim;

use Illuminate\Support\Facades\Route;
use App\Facades\XeToggleMenu;
use Xpressengine\Plugin\AbstractPlugin;
use Xpressengine\Plugins\Claim\Models\ClaimLog;
use Xpressengine\Plugins\Claim\Factory\ClaimFactory;
use Xpressengine\Plugins\Claim\Repositories\ClaimRepository;

/**
 * Class Plugin
 * @package Xpressengine\Plugins\Claim
 */
class Plugin extends AbstractPlugin
{
    /**
     * @var Migrations\MigrationResource
     */
    protected $migrationResource;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->migrationResource = app(Migrations\MigrationResource::class);

        parent::__construct();
    }

    /**
     * boot
     * @return void
     */
    public function boot()
    {
        $this->registerRoute();
        $this->registerSettingsMenu();

        ClaimRepository::setModel(ClaimLog::class);

        app()->singleton(ClaimHandler::class, function () {
            $proxyClass = app('xe.interception')->proxy(ClaimHandler::class);
            return new $proxyClass(app(ClaimRepository::class), app('xe.config'));
        });
        app()->alias(ClaimHandler::class, 'xe.claim.handler');

        app()->singleton(ClaimFactory::class, function () {
            return new ClaimFactory(app(ClaimHandler::class));
        });
    }

    /**
     * check for updates
     * @return boolean
     */
    public function checkUpdated($installedVersion = null)
    {
        if ($this->migrationResource->checkInstalled() === false) {
            return false;
        }

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
        if ($this->migrationResource->checkInstalled() === false) {
            $this->migrationResource->install();
        }

        $this->putLang();
        $this->setToggleMenuConfigs();
    }

    public function install()
    {
        if ($this->migrationResource->checkInstalled() === false) {
            $this->migrationResource->install();
        }

        $this->putLang();
        $this->setToggleMenuConfigs();
    }

    /**
     * set toggle menu item to board, comment, user
     * @return void
     */
    protected function setToggleMenuConfigs()
    {
        $this->setToggleMenuConfig('user', 'user/toggleMenu/claim@userClaimItem');
        $this->setToggleMenuConfig('comment', 'comment/toggleMenu/claim@commentClaimItem');
        $this->setToggleMenuConfig('module/board@board', 'module/board@board/toggleMenu/claim@boardClaimItem');
    }

    protected function setToggleMenuConfig(string $toggleMenuId, string $itemId)
    {
        $activated = XeToggleMenu::getActivated($toggleMenuId);
        if (isset($activated[$itemId]) === false) {
            $setActivate = array_keys($activated);
            $setActivate[] = $itemId;
            XeToggleMenu::setActivates($toggleMenuId, null, $setActivate);
        }
    }

    /**
     * put lang
     * @return void
     */
    protected function putLang()
    {
        app('xe.translator')->putFromLangDataSource(
            static::getId(),
            static::path('langs/lang.php')
        );
    }

    /**
     * register route
     * @return void
     */
    protected function registerRoute()
    {
        Route::middleware('web')->group(static::path('routes.php'));
    }

    /**
     * register interception
     * @return void
     */
    protected function registerSettingsMenu()
    {
        $menus = [
            'contents.claim' => [
                'title' => 'xe::claim',
                'display' => true,
                'description' => 'xe::claim',
                'ordering' => 5000
            ],
        ];
        foreach ($menus as $id => $menu) {
            app('xe.register')->push('settings/menu', $id, $menu);
        }
    }
}
