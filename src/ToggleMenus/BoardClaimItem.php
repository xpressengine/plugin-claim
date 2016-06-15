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

/**
 * BoardClaimItem
 *
 * @category    Claim
 * @package     Claim
 */
class BoardClaimItem extends AbstractToggleMenu
{
    /**
     * get name
     *
     * @return string
     */
    public static function getName()
    {
        return '게시물 신고';
    }

    /**
     * get description
     *
     * @return string
     */
    public static function getDescription()
    {
        return '선택한 문서를 신고합니다.';
    }


    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $target;

    /**
     * create instance
     *
     * @param string $type   type
     * @param string $target target
     */
    public function __construct($type, $target)
    {
        $this->type = $type;
        $this->target = $target;
    }

    /**
     * get text
     *
     * @return string
     */
    public function getText()
    {
        /** @var Handler $handler */
        $handler = app('xe.claim.handler');

        $handler->set($this->type);
        $count = $handler->count($this->target);
        $invoked = $handler->has($this->target, Auth::user());

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
        return 'exec';
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

        $handler->set($this->type);

        $action = '';
        if ($handler->has($this->target, Auth::user()) === true) {
            $action = sprintf(
                'ClaimToggleMenu.storeBoard(event, "%s", "%s", "%s")',
                route('fixed.claim.destroy'),
                $this->type,
                $this->target
            );
        } else {
            $action = sprintf(
                'ClaimToggleMenu.storeBoard(event, "%s", "%s", "%s", "%s")',
                route('fixed.claim.store'),
                $this->type,
                $this->target,
                $_SERVER['HTTP_REFERER']
            );
        }

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

    /**
     * get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return null;
    }
}
