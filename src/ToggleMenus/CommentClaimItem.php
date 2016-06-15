<?php
/**
 * CommentClaimItem
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
use Auth;

/**
 * Claim comment toggle menu
 *
 * @category    Claim
 * @package     Claim
 */
class CommentClaimItem extends BoardClaimItem
{
    /**
     * get name
     * @return string
     */
    public static function getName()
    {
        return '댓글 신고';
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
}
