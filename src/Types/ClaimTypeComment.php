<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\Plugins\Claim\Handlers\CommentClaimTypeHandler;

/**
 * Class ClaimTypeComment
 * @package Xpressengine\Plugins\Claim\Types
 */
class ClaimTypeComment extends AbstractClaimType
{
    /**
     * claim type
     * @var string
     */
    protected $name = 'comment';

    /**
     * text of claim type
     * @var string
     */
    protected $text = 'claim::claimTypeComment';

    /**
     * class of claim type
     * @var string
     */
    protected $class = '\Xpressengine\Plugins\Comment\Models\Comment';

    public function __construct()
    {
        $this->handler = app(CommentClaimTypeHandler::class, ['claimType' => $this]);
    }
}
