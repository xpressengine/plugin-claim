<?php
/**
 * Claim user controller
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
namespace Xpressengine\Plugins\Claim\Controllers;

use Input;
use XePresenter;
use Auth;
use App\Http\Controllers\Controller;
use Xpressengine\Plugins\Claim\Exceptions\AlreadyClaimedHttpException;

/**
 * Claim user controller
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
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

        $invoked = $this->handler->invoked($targetId, Auth::user());
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
