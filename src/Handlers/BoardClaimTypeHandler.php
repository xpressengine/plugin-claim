<?php

namespace Xpressengine\Plugins\Claim\Handlers;

use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\Exceptions\CantReportAdminException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportMyselfException;

/**
 * Class BoardClaimTypeHandler
 * @package Xpressengine\Plugins\Claim\Types
 * @template T
 */
class BoardClaimTypeHandler extends AbstractClaimTypeHandler
{
    /**
     * report target
     * @return void
     */
    public function report(
        UserInterface $author,
        string $targetId,
        string $shortCut,
        string $message = '',
        string $targetUserId = ''
    ) {
        /** @var \Xpressengine\Plugins\Board\Models\Board $target */
        $target = \Xpressengine\Plugins\Board\Models\Board::findOrFail($targetId);

        $this->checkReportConditions($author, $target);

        parent::report($author, $targetId, $shortCut, $message, $target->user_id);
    }

    /**
     * check report conditions
     * @param UserInterface $author
     * @param $target
     * @return void
     */
    public function checkReportConditions(
        UserInterface $author,
        $target
    ) {
        assert($target instanceof \Xpressengine\Plugins\Board\Models\Board);

        if (($targetUser = $target->getAuthor()) && $targetUser instanceof User) {
            if ($targetUser->getKey() === $author->getKey()) {
                throw new CantReportMyselfException();
            }

            if ($targetUser->isAdmin()) {
                throw new CantReportAdminException();
            }
        }

        parent::checkReportConditions($author, $target->getKey());
    }
}
