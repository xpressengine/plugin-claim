<?php
/**
 * SettingsSkin.php
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

namespace Xpressengine\Plugins\Claim\Skin;

use Xpressengine\Skin\AbstractSkin;
use View;

/**
 * SettingsSkin
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
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
        $view->content = $content->render();

        return $view;
    }
}
