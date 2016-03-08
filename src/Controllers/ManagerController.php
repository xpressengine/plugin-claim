<?php
/**
 * Claim manager controller
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

use App\Sections\CommentSection;
use App\Sections\DynamicFieldSection;
use Input;
use View;
use Redirect;
use Exception;
use XePresenter;
use App;
use XeDB;
use Xpressengine\Config\ConfigEntity;
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
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
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
        XePresenter::setSettingsSkin('claim');
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
