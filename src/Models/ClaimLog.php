<?php
/**
 * ClaimLog.php
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

namespace Xpressengine\Plugins\Claim\Models;

use Xpressengine\Config\ConfigEntity;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Document\Exceptions\NotAllowedTypeException;
use Xpressengine\Document\Exceptions\DocumentNotFoundException;
use Xpressengine\Document\Exceptions\ReplyLimitationException;
use Xpressengine\Document\Exceptions\ValueRequiredException;
use Illuminate\Database\Eloquent\Builder as OriginBuilder;
use Xpressengine\User\Models\User;

/**
 * ClaimLog
 *
 * @property int    id
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
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class ClaimLog extends DynamicModel
{
    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function claimable()
    {
        return $this->morphTo(null, 'claim_type', 'target_id');
    }
}
