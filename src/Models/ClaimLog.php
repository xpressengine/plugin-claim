<?php
/**
 * ClaimLog
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
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
 */
class ClaimLog extends DynamicModel
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('Xpressengine\User\Models\User', 'userId');
    }
}
