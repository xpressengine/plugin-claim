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

use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Category\CategoryHandler;
use Xpressengine\Http\Request;
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
    public function __construct(Handler $handler)
    {
        XePresenter::setSettingsSkinTargetId('claim');
        $this->handler = $handler;
    }

    /**
     * index
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function index(Request $request)
    {
        $config = $this->handler->getConfig();
        $paginate = ClaimLog::orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->except('page'));

        return XePresenter::make('index', [
            'action' => 'index',
            'config' => $config,
            'paginate' => $paginate,
        ]);
    }

    /**
     * claim's config
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function config()
    {
        $config = $this->handler->getConfig();

        return XePresenter::make('config', [
            'action' => 'config',
            'config' => $config
        ]);
    }

    /**
     * update config
     *
     * @param  Request  $request
     * @param  CategoryHandler  $categoryHandler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateConfig(Request $request, CategoryHandler $categoryHandler)
    {
        $inputs = $request->except(['categoryId']);
        $config = $this->handler->getConfig();

        foreach ($inputs as $key => $input) {
            $config->set($key, $input);
        }

        // Store clains's category (temp)
        if ($config->get('category') === true) {
            $categoryId = $config->get('categoryId');
            $category = $categoryId !== null ? $categoryHandler->cates()->find($categoryId) : null;

            if ($category === null) {
                $input = ['name' => 'xe::claim'];
                $category = $categoryHandler->createCate($input);

                $config->set('categoryId', $category->id);
            }
        }

        app('xe.config')->modify($config);
        return redirect()->route('manage.claim.claim.config');
    }

    /**
     * Store clains's category
     *
     * @param  CategoryHandler  $categoryHandler  category handler
     * @return \Xpressengine\Presenter\Presentable
     */
    public function storeCategory(CategoryHandler $categoryHandler)
    {
        $config = $this->handler->getConfig();

        $categoryId  = $config->get('categoryId');
        $category = $categoryId !== null ? $categoryHandler->cates()->find($categoryId) : null;

        if ($category === null) {
            $input = ['name' => 'xe::claim'];
            $category = $categoryHandler->createCate($input);

            $config->set('categoryId', $category->id);
            app('xe.config')->modify($config);
        }

        return XePresenter::makeApi($category->getAttributes());
    }

    /**
     * delete
     *
     * @return \Xpressengine\Presenter\Presentable
     */
    public function delete(Request $request)
    {
        $id =$request->get('id');
        $this->handler->remove($id);
        return XePresenter::makeApi([]);
    }
}
