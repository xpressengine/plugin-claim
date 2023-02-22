<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\Plugins\Claim\Handlers\UserClaimTypeHandler;

/**
 * Class ClaimTypeUser
 * @package Xpressengine\Plugins\Claim\Types
 */
class ClaimTypeUser extends AbstractClaimType
{
    /**
     * claim type
     * @var string
     */
    protected $name = 'user';

    /**
     * text of claim type
     * @var string
     */
    protected $text = 'claim::claimTypeUser';

    /**
     * class of claim type
     * @var string
     */
    protected $class = '\Xpressengine\User\Models\User';

    public function __construct()
    {
        $this->handler = app(UserClaimTypeHandler::class, ['claimType' => $this]);
    }
}
