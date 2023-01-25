<?php
/**
 * ClaimController.php
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

use Auth;
use XePresenter;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Xpressengine\Support\Exceptions\LoginRequiredHttpException;
use Xpressengine\Plugins\Claim\Handler;
use Xpressengine\Plugins\Claim\Exceptions\ClaimException;
use Xpressengine\Plugins\Claim\Exceptions\NotSupportClaimTypeException;

/**
 * ClaimController
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class ClaimController extends Controller
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

        try {
            $this->handler->set($from);
            $invoked = $this->handler->has($targetId, Auth::user());
            $count = $this->handler->count($targetId);
        } catch (ClaimException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw new NotSupportClaimTypeException();
        } catch (\Exception $e) {
            throw new ClaimException();
        }

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
    public function store(Request $request)
    {
        if (Auth::check() === false) {
            throw new LoginRequiredHttpException;
        }

        $targetId = $request->get('targetId');
        $shortCut = $request->get('shortCut');
        $from = $request->get('from');
        $message = $request->get('message') ?: '';

        try {
            $this->handler->set($from);
            $this->handler->report($targetId, Auth::user(), $shortCut, $message);
        } catch (ClaimException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw new NotSupportClaimTypeException();
        } catch (\Exception $e) {
            throw new ClaimException();
        }

        return $this->index($request);
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

        try {
            $this->handler->set($from);
            $this->handler->removeByTargetId($targetId, Auth::user());
        } catch (ClaimException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw new NotSupportClaimTypeException();
        } catch (\Exception $e) {
            throw new ClaimException();
        }

        return $this->index($request);
    }
}
