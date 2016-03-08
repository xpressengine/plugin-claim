<?php
/**
 * ClaimLog
 *
 * PHP version 5
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Claim\Models;

use Xpressengine\Config\ConfigEntity;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Document\Exceptions\NotAllowedTypeException;
use Xpressengine\Document\Exceptions\DocumentNotFoundException;
use Xpressengine\Document\Exceptions\ReplyLimitationException;
use Xpressengine\Document\Exceptions\ValueRequiredException;
use Illuminate\Database\Eloquent\Builder as OriginBuilder;

/**
 * ClaimLog
 *
 * @property int id
 * @property string claimType
 * @property string shortCut
 * @property string targetId
 * @property string uiserId
 * @property string ipaddress
 * @property string message
 * @property string createdAt
 * @property string updatedAt
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class ClaimLog extends DynamicModel
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('Xpressengine\User\Models\User', 'userId');
    }
}
