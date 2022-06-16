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

use XePresenter;
use Auth;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
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
    public function index(Request $request)
    {
        $targetId = $request->get('targetId');
        $from = $request->get('from');

        $this->handler->set($from);

        $invoked = $this->handler->has($targetId, Auth::user());
        $count = $this->handler->count($targetId);

        return XePresenter::makeApi([
            'invoked' => $invoked,
            'count' => $count,
        ]);
    }

    /**
     * modal
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function modal(Request $request)
    {
        $config = $this->handler->getConfig();

        return api_render('claim::views.modal', [
            'config' => $config
        ]);
    }

    /**
     * store
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function store(Request $request)
    {
        if (Auth::check() === false) {
            throw new LoginRequiredHttpException;
        }

        $from = $request->get('from');
        $targetId = $request->get('targetId');
        $shortCut = $request->get('shortCut');
        $categoryItem = $request->get('categoryItem');

        $this->handler->set($from);

        try {
            $this->handler->add($targetId, Auth::user(), $shortCut, $categoryItem);
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
    public function destroy(Request $request)
    {
        $targetId = $request->get('targetId');
        $from = $request->get('from');

        $this->handler->set($from);

        $this->handler->removeByTargetId($targetId, Auth::user());

        return $this->index();
    }
}
