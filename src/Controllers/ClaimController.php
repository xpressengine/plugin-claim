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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facades\XePresenter;
use Xpressengine\Plugins\Claim\ClaimHandler;
use Xpressengine\Plugins\Claim\Factory\ClaimFactory;
use Xpressengine\Support\Exceptions\LoginRequiredHttpException;

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
     * @var ClaimHandler
     */
    protected $handler;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->handler = app('xe.claim.handler');
    }

    /**
     * @return \Xpressengine\Presenter\Presentable
     */
    public function index(Request $request)
    {
        $targetId = $request->get('targetId');
        $claimType = $request->get('from');

        $invoked = $this->handler->exists($claimType, auth()->user(), $targetId);
        $count = $this->handler->count($claimType, $targetId);

        return XePresenter::makeApi([
            'invoked' => $invoked,
            'count' => $count,
        ]);
    }

    /**
     * @return \Xpressengine\Presenter\Presentable
     */
    public function store(ClaimFactory $factory, Request $request)
    {
        if (auth()->check() === false) {
            throw new LoginRequiredHttpException;
        }

        $author = auth()->user();
        $targetId = $request->get('targetId');
        $shortCut = $request->get('shortCut');
        $claimType = $request->get('from');
        $message = $request->get('message') ?: '';

        $targetClaimType = $factory->make($claimType);
        $targetClaimType->report($author, $targetId, $shortCut, $message);

        return $this->index($request);
    }

    /**
     * @return \Xpressengine\Presenter\Presentable
     */
    public function destroy(Request $request)
    {
        $targetId = $request->get('targetId');
        $claimType = $request->get('from');

        $this->handler->deleteByTargetId($claimType, $targetId, auth()->user());

        return $this->index($request);
    }
}
