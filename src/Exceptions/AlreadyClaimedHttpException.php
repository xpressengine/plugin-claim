<?php
/**
 * AlreadyClaimedHttpException
 *
 * PHP version 5
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Claim\Exceptions;

use Xpressengine\Plugins\Board\HttpBoardException;
use Symfony\Component\HttpFoundation\Response;

/**
 * AlreadyClaimedHttpException
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 * @deprecated
 */
class AlreadyClaimedHttpException extends HttpBoardException
{
    protected $message = 'claim::AlreadyClaimed';
    protected $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
}
