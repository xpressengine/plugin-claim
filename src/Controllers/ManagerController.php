<?php
/**
 * Claim manager controller
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
use View;
use Redirect;
use XePresenter;
use App;
use XeDB;
use Cfg;
use DynamicField;
use Validator;
use App\Http\Controllers\Controller;
use Xpressengine\Plugins\Claim\Models\ClaimLog;

/**
 * Claim manager controller
 *
 * @category    Claim
 * @package     Claim
 */
class ManagerController extends Controller
{

    /**
     * @var ClaimHandler
     */
    protected $handler;

    /**
     * create instance
     */
    public function __construct()
    {
        XePresenter::setSettingsSkinTargetId('claim');
    }


    /**
     * index
     *
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function index()
    {
        $wheres = [];
        $orders = [];

        $paginate = ClaimLog::paginate(20);

        return XePresenter::make('index', [
            'action' => 'index',
            'paginate' => $paginate,
        ]);
    }

    /**
     * delete
     *
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function delete()
    {
        $id = Input::get('id');

        $this->handler->remove($id);

        return XePresenter::makeApi([]);
    }
}
