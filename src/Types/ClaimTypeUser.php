<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\Handler as ClaimHandler;
use Xpressengine\Plugins\Claim\Exceptions\CantReportAdminException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportMyselfException;
use Xpressengine\Plugins\Claim\Exceptions\GuestCannotReportException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportWithdrawnUserException;

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
    protected $text = '';

    /**
     * class of claim type
     * @var string
     */
    protected $class = '\Xpressengine\User\Models\User';

    /**
     * register claim type
     * @return void
     */
    public function register()
    {
    }

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
        /** @var User $target */
        $target = User::findOrFail($targetId);

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
        if (($author instanceof User) === false) {
            throw new GuestCannotReportException();
        }

        if ($target->getId() === $author->getId()) {
            throw new CantReportMyselfException();
        }

        if ($target->isAdmin()) {
            throw new CantReportAdminException();
        }

        if ($target->getStatus() !== User::STATUS_ACTIVATED) {
            throw new CantReportWithdrawnUserException();
        }

        parent::checkReportConditions($handler, $author, $target->getId());
    }
}
