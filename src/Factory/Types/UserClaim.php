<?php

namespace Xpressengine\Plugins\Claim\Factory\Types;

use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\Exceptions\CantReportAdminException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportMyselfException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportWithdrawnUserException;
use Xpressengine\Plugins\Claim\Exceptions\GuestCannotReportException;

class UserClaim extends AbstractClaimType
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
