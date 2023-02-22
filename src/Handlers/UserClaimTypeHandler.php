<?php

namespace Xpressengine\Plugins\Claim\Handlers;

use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\Exceptions\CantReportAdminException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportMyselfException;
use Xpressengine\Plugins\Claim\Exceptions\GuestCannotReportException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportWithdrawnUserException;

/**
 * Class UserClaimTypeHandler
 * @package Xpressengine\Plugins\Claim\Types
 * @template T
 */
class UserClaimTypeHandler extends AbstractClaimTypeHandler
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
        /** @var User $target */
        $target = User::findOrFail($targetId);

        $this->checkReportConditions($author, $target);

        parent::report($author, $targetId, $shortCut, $message, $target->getKey());
    }

    /**
     * check report conditions
     * @param UserInterface $author
     * @param $target
     * @return void
     */
    public function checkReportConditions(UserInterface $author, $target)
    {
        if (($author instanceof User) === false) {
            throw new GuestCannotReportException();
        }

        if ($target->getKey() === $author->getKey()) {
            throw new CantReportMyselfException();
        }

        if ($target->isAdmin()) {
            throw new CantReportAdminException();
        }

        if ($target->getStatus() !== User::STATUS_ACTIVATED) {
            throw new CantReportWithdrawnUserException();
        }

        parent::checkReportConditions($author, $target->getKey());
    }
}
