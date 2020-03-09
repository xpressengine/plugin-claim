<?php
/**
 * ManagerController.php
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
use View;
use Redirect;
use XePresenter;
use App;
use XeDB;
use Cfg;
use DynamicField;
use Validator;
use App\Http\Controllers\Controller;
use Xpressengine\Plugins\Claim\Handler;
use Xpressengine\Plugins\Claim\Models\ClaimLog;

/**
 * ManagerController
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class ManagerController extends Controller
{
    /**
     * @var Handler
     */
    protected $handler;

    /**
     * ManagerController constructor.
     */
    public function __construct()
    {
        XePresenter::setSettingsSkinTargetId('claim');
    }

    /**
     * index
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function index()
    {
        $wheres = [];
        $orders = [];

        $paginate = ClaimLog::orderBy('created_at', 'desc')->paginate(20)->appends(Request::except('page'));

        return XePresenter::make('index', [
            'action' => 'index',
            'paginate' => $paginate,
        ]);
    }

    /**
     * delete
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function delete()
    {
        $id = Request::get('id');

        $this->handler->remove($id);

        return XePresenter::makeApi([]);
    }
}
