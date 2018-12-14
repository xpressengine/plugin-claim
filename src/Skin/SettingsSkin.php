<?php
/**
 * SettingsSkin.php
 *
 * This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link        http://www.xpressengine.com
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
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
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
        $view->content = $content->render();

        return $view;
    }
}
