<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\Handler as ClaimHandler;
use Xpressengine\Plugins\Claim\Exceptions\CantReportAdminException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportMyselfException;

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
    protected $text = '';

    /**
     * class of claim type
     * @var string
     */
    protected $class = '\Xpressengine\Plugins\Board\Models\Board';

    /**
     * report target
     * @return void
     */
    public function report(
        ClaimHandler $handler,
        UserInterface $author,
        string $targetId,
        string $shortCut,
        string $message = ''
    ) {
        /** @var \Xpressengine\Plugins\Board\Models\Board $target */
        $target = \Xpressengine\Plugins\Board\Models\Board::findOrFail($targetId);

        $this->checkReportConditions($handler, $author, $target);

        parent::report($handler, $author, $targetId, $shortCut, $message);
    }

    /**
     * check report conditions
     * @param ClaimHandler $handler
     * @param UserInterface $author
     * @param $target
     * @return void
     */
    public function checkReportConditions(
        ClaimHandler $handler,
        UserInterface $author,
        $target
    ) {
        /** @var \Xpressengine\Plugins\Board\Models\Board $target */
        if (($targetUser = $target->getAuthor()) && $targetUser instanceof User) {
            if ($targetUser->getId() === $author->getId()) {
                throw new CantReportMyselfException();
            }

            if ($targetUser->isAdmin()) {
                throw new CantReportAdminException();
            }
        }

        parent::checkReportConditions($handler, $author, $target->getId());
    }
}
