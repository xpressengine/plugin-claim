<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\Exceptions\CantReportAdminException;
use Xpressengine\Plugins\Claim\Exceptions\CantReportMyselfException;

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
    protected $text = '';

    /**
     * class of claim type
     * @var string
     */
    protected $class = '\Xpressengine\Plugins\Comment\Models\Comment';

    /**
     * report target
     * @return void
     */
    public function report(
        UserInterface $author,
        string $targetId,
        string $shortCut,
        string $message = ''
    ) {
        /** @var \Xpressengine\Plugins\Comment\Models\Comment $target */
        $target = \Xpressengine\Plugins\Comment\Models\Comment::findOrFail($targetId);

        $this->checkReportConditions($author, $target);

        parent::report($author, $targetId, $shortCut, $message);
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
        assert($target instanceof \Xpressengine\Plugins\Comment\Models\Comment);

        if (($targetUser = $target->getAuthor()) && $targetUser instanceof User) {
            if ($targetUser->getKey() === $author->getKey()) {
                throw new CantReportMyselfException();
            }

            if ($targetUser->isAdmin()) {
                throw new CantReportAdminException();
            }
        }

        parent::checkReportConditions($author, $target->id);
    }
}
