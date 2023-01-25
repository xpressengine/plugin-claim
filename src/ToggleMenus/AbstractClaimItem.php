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
    /**
     * get text
     *
     * @return string
     */
    public function getText()
    {
        $handler = $this->claimHandler();

        $count = $handler->count($this->identifier);
        $invoked = $handler->has($this->identifier, Auth::user());

        $text = 'xe::claim';
        if ($invoked === true) {
            $text = 'xe::cancelClaim';
        }

        if ($count > 0) {
            return sprintf('%s (%s)', xe_trans($text), $count);
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
        $handler = $this->claimHandler();

        XeFrontend::translation([
            'claim::msgClaimReceived',
            'claim::msgClaimCanceled',
            'claim::enterClaimReason'
        ]);

        if ($handler->has($this->identifier, Auth::user()) === true) {
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

    /**
     * @return Handler
     */
    private function claimHandler()
    {
        $handler = app('xe.claim.handler');
        $handler->set($this->componentType);

        return $handler;
    }
}
