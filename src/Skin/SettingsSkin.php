<?php
/**
 * Claim manager skin
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Claim\Skin;

use Xpressengine\Skin\AbstractSkin;
use View;

/**
 * Claim manager skin
 *
 * @category    Claim
 * @package     Claim
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
