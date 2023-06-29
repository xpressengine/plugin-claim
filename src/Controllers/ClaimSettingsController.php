<?php
/**
 * ClaimSettingsController.php
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
use App\Facades\XeDB;
use App\Facades\XePresenter;
use Xpressengine\Plugins\Claim\ClaimHandler;
use Xpressengine\Plugins\Claim\Factory\ClaimFactory;
use Xpressengine\Plugins\Claim\Models\ClaimLog;

/**
 * ClaimSettingsController
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class ClaimSettingsController extends Controller
{
    /**
     * @var ClaimHandler
     */
    protected $handler;

    /**
     * @var ClaimFactory
     */
    protected $factory;

    /**
     * @return void
     */
    public function __construct(ClaimHandler $handler, ClaimFactory $factory)
    {
        $this->handler = $handler;
        $this->factory = $factory;
    }

    /**
     * @return \Xpressengine\Presenter\Presentable
     */
    public function index(Request $request)
    {
        $paginate = $this->handler->paginateClaimLogs($request->all());
        $claimTypes = $this->factory->getActivateTypes();
        $claimStatuses = ClaimLog::STATUSES;

        return XePresenter::make('claim::views.settings.index', [
            'paginate' => $paginate,
            'claimTypes' => $claimTypes,
            'claimStatuses' => $claimStatuses
        ]);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function edit(string $id)
    {
        $log = $this->handler->findOrFail($id);
        $claimStatuses = ClaimLog::STATUSES;
        $targetClaimTypeText = xe_trans($this->handler->getClaimTypeByKey($log->claim_type)->getText());

        session()->flash('settings_intended_url', url()->previous());

        return XePresenter::make('claim::views.settings.edit', [
            'log' => $log,
            'claimStatuses' => $claimStatuses,
            'targetClaimTypeText' => $targetClaimTypeText
        ]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, string $id)
    {
        $status = $request->get('status');
        $adminMessage = $request->get('admin_message') ?: '';

        XeDB::beginTransaction();
        try {
            $this->handler->updateLog($id, $status, $adminMessage);
            XeDB::commit();
        } catch (\Exception $e) {
            XeDB::rollback();
            throw $e;
        }

        return redirect()->intended(session()->get('settings_intended_url'))
            ->with('alert', ['type' => 'success', 'message' => xe_trans('xe::saved')]);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(string $id)
    {
        $this->handler->delete($id);

        return redirect()->back()->with('alert', ['type' => 'success', 'message' => xe_trans('xe::deleted')]);
    }
}
