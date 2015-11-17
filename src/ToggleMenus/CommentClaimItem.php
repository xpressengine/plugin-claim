<?php
/**
 * CommentClaimItem
 *
 * PHP version 5
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Claim\ToggleMenus;

use Xpressengine\ToggleMenu\AbstractToggleMenu;
use Auth;

/**
 * Claim comment toggle menu
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class CommentClaimItem extends AbstractToggleMenu
{
    /**
     * @var string
     */
    public static $id = 'comment_service/toggleMenu/claim@commentClaimItem';

    /**
     * get id
     *
     * @return string
     */
    public static function getId()
    {
        return self::$id;
    }

    /**
     * get name
     * @return string
     */
    public static function getName()
    {
        return '신고';
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
        $handler = app('xe.claim.handler');

        $handler->set($this->type);
        $count = $handler->count($this->target);
        $invoked = $handler->invoked($this->target, Auth::user());

        $text = '신고';
        if ($invoked === true) {
            $text = '신고 취소';
        }

        if ($count > 0) {
            $text = sprintf('%s (%s)', $text, $count);
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
        $handler = app('xe.claim.handler');

        $handler->set($this->type);
        $invoked = $handler->invoked($this->target, Auth::user());

        $action = sprintf(
            'ClaimToggleMenu.storeBoard(e, "%s", "%s", "%s", "%s")',
            route('fixed.claim.store'),
            $this->type,
            $this->target,
            $_SERVER['HTTP_REFERER']
        );
        if ($invoked === true) {
            $action = sprintf(
                'ClaimToggleMenu.storeBoard(e, "%s", "%s", "%s")',
                route('fixed.claim.destroy'),
                $this->type,
                $this->target
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
     * @return string|null
     */
    public function getIcon()
    {
        return null;
    }
}
