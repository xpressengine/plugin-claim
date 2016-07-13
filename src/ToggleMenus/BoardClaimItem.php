<?php
/**
 * BoardClaimItem
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Claim\ToggleMenus;

use Xpressengine\ToggleMenu\AbstractToggleMenu;
use Xpressengine\Plugins\Claim\Handler;
use Auth;
use XeFrontend;

/**
 * BoardClaimItem
 *
 * @category    Claim
 * @package     Claim
 */
class BoardClaimItem extends AbstractToggleMenu
{
    /**
     * get text
     *
     * @return string
     */
    public function getText()
    {
        /** @var Handler $handler */
        $handler = app('xe.claim.handler');

        $handler->set($this->componentType);
        $count = $handler->count($this->identifier);
        $invoked = $handler->has($this->identifier, Auth::user());

        $text = 'xe::claim';
        if ($invoked === true) {
            $text = 'xe::cancelClaim';
        }

        if ($count > 0) {
            $text = sprintf('%s (%s)', xe_trans($text), $count);
        } else {
            $text = xe_trans($text);
        }
        return $text;
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
        /** @var Handler $handler */
        $handler = app('xe.claim.handler');

        $handler->set($this->componentType);

        $action = '';
        if ($handler->has($this->identifier, Auth::user()) === true) {
            $action = sprintf(
                'ClaimToggleMenu.storeBoard(event, "%s", "%s", "%s")',
                route('fixed.claim.destroy'),
                $this->componentType,
                $this->identifier
            );
        } else {
            $action = sprintf(
                'ClaimToggleMenu.storeBoard(event, "%s", "%s", "%s", "%s")',
                route('fixed.claim.store'),
                $this->componentType,
                $this->identifier,
                $_SERVER['HTTP_REFERER']
            );
        }

        XeFrontend::translation([
            'claim::msgClaimReceived',
            'claim::msgClaimCanceled',
        ]);
        return $action;
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
