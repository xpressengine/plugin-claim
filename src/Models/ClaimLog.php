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

use Xpressengine\User\Models\User;
use Xpressengine\Database\Eloquent\DynamicModel;

/**
 * ClaimLog
 *
 * @property int    id
 * @property string claim_type
 * @property string short_cut
 * @property string target_id
 * @property string user_id
 * @property string ipaddress
 * @property string message
 * @property string created_at
 * @property string updated_at
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
