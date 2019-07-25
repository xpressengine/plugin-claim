<?php
/**
 * UserController.php
 *
 * This file is part of the Xpressengine package.
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

namespace Xpressengine\Plugins\Claim\Controllers;

use Request;
use XePresenter;
use Auth;
use App\Http\Controllers\Controller;
use Xpressengine\Plugins\Claim\Exceptions\AlreadyClaimedHttpException;
use Xpressengine\Plugins\Claim\Handler;
use Xpressengine\Support\Exceptions\LoginRequiredHttpException;

/**
 * UserController
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class UserController extends Controller
{
    /**
     * @var Handler
     */
    protected $handler;

    /**
     * create instance
     */
    public function __construct()
    {
        $this->handler = app('xe.claim.handler');
    }

    /**
     * index
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function index()
    {
        $targetId = Request::get('targetId');
        $from = Request::get('from');

        $this->handler->set($from);

        $invoked = $this->handler->has($targetId, Auth::user());
        $count = $this->handler->count($targetId);

        return XePresenter::makeApi([
            'invoked' => $invoked,
            'count' => $count,
        ]);
    }

    /**
     * store
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function store()
    {
        if (Auth::check() === false) {
            throw new LoginRequiredHttpException;
        }

        $targetId = Request::get('targetId');
        $shortCut = Request::get('shortCut');
        $from = Request::get('from');

        $this->handler->set($from);

        try {
            $this->handler->add($targetId, Auth::user(), $shortCut);
        } catch (\Exception $e) {
            throw new AlreadyClaimedHttpException;
        }
        return $this->index();
    }

    /**
     * destroy
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function destroy()
    {
        $targetId = Request::get('targetId');
        $from = Request::get('from');

        $this->handler->set($from);

        $this->handler->removeByTargetId($targetId, Auth::user());

        return $this->index();
    }
}
