<?php
/**
 * Claim manager skin
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
namespace Xpressengine\Plugins\Claim\Skin;

use Xpressengine\Skin\AbstractSkin;
use View;

/**
 * Claim manager skin
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class SettingsSkin extends AbstractSkin
{

    /**
     * @var string
     */
    public static $id = 'claim/settingsSkin/claim@default';

    /**
     * render
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $view = View::make('claim::views.settingsSkin._frame', $this->data);
        $content = View::make(sprintf('claim::views.settingsSkin.%s', $this->view), $this->data);
        $content->tabMenu = View::make('claim::views.settingsSkin._tabMenu', $this->data)->render();
        $view->content = $content->render();

        return $view;
    }
}
