<?php
/**
 * Claim user controller
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Claim\Controllers;

use Input;
use XePresenter;
use Auth;
use App\Http\Controllers\Controller;
use Xpressengine\Plugins\Claim\Exceptions\AlreadyClaimedHttpException;
use Xpressengine\Plugins\Claim\Handler;
use Xpressengine\Support\Exceptions\LoginRequiredHttpException;

/**
 * Claim user controller
 *
 * @category    Claim
 * @package     Claim
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
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function index()
    {
        $targetId = Input::get('targetId');
        $from = Input::get('from');

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
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function store()
    {
        if (Auth::check() === false) {
            throw new LoginRequiredHttpException;
        }

        $targetId = Input::get('targetId');
        $shortCut = Input::get('shortCut');
        $from = Input::get('from');

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
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function destroy()
    {
        $targetId = Input::get('targetId');
        $from = Input::get('from');

        $this->handler->set($from);

        $this->handler->removeByTargetId($targetId, Auth::user());

        return $this->index();
    }
}
