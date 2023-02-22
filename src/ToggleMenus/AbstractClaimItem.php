<?php

namespace Xpressengine\Plugins\Claim\ToggleMenus;

use Auth;
use XeFrontend;
use Xpressengine\Plugins\Claim\Handler;
use Xpressengine\ToggleMenu\AbstractToggleMenu;

/**
 * Class AbstractClaimItem
 *
 * @package Xpressengine\Plugins\Claim\ToggleMenus
 */
abstract class AbstractClaimItem extends AbstractToggleMenu
{
    /** @var Handler */
    protected $claimHandler;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->claimHandler = app(Handler::class);
    }

    /**
     * get text
     *
     * @return string
     */
    public function getText()
    {
        $text = 'xe::claim';
        if ($this->claimHandler->exists($this->componentType, $this->identifier, Auth::user())) {
            $text = 'xe::cancelClaim';
        }

        return xe_trans($text);
    }

    /**
     * get toggle menu type
     *
     * @return string
     */
    public function getType()
    {
        return static::MENUTYPE_EXEC;
    }

    /**
     * get toggle menu action
     *
     * @return string
     */
    public function getAction()
    {
        XeFrontend::translation([
            'claim::msgClaimReceived',
            'claim::msgClaimCanceled',
            'claim::enterClaimReason'
        ]);

        if ($this->claimHandler->exists($this->componentType, $this->identifier, Auth::user())) {
            return sprintf(
                'ClaimToggleMenu.destroyClaim(event, "%s", "%s", "%s")',
                route('fixed.claim.destroy'),
                $this->componentType,
                $this->identifier
            );
        }

        return sprintf(
            'ClaimToggleMenu.storeClaim(event, "%s", "%s", "%s", "%s")',
            route('fixed.claim.store'),
            $this->componentType,
            $this->identifier,
            request()->headers->get('referer')
        );
    }

    /**
     * get javascript
     *
     * @return string
     */
    public function getScript()
    {
        $path = '/plugins/claim/assets/menu.js';
        return asset(str_replace(base_path(), '', $path));
    }
}
