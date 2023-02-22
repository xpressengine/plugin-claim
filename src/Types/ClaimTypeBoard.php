<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\Plugins\Claim\Handlers\BoardClaimTypeHandler;

/**
 * Class ClaimTypeBoard
 * @package Xpressengine\Plugins\Claim\Types
 */
class ClaimTypeBoard extends AbstractClaimType
{
    /**
     * claim type
     * @var string
     */
    protected $name = 'module/board@board';

    /**
     * text of claim type
     * @var string
     */
    protected $text = 'claim::claimTypeBoard';

    /**
     * class of claim type
     * @var string
     */
    protected $class = '\Xpressengine\Plugins\Board\Models\Board';

    public function __construct()
    {
        $this->handler = app(BoardClaimTypeHandler::class, ['claimType' => $this]);
    }
}
