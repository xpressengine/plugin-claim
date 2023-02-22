<?php

namespace Xpressengine\Plugins\Claim\Handlers;

use Xpressengine\Plugins\Claim\Types\AbstractClaimType;
use Xpressengine\User\UserInterface;

/**
 * Class AbstractClaimTypeHandler
 * @package Xpressengine\Plugins\Claim\Types
 * @template T
 */
abstract class AbstractClaimTypeHandler
{
    /** @var AbstractClaimType */
    protected $claimType;

    public function __construct(AbstractClaimType $claimType)
    {
         $this->claimType = $claimType;
    }

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
        app('xe.claim.handler')->addLog(
            $this->claimType->getName(),
            $targetId,
            $targetUserId,
            $author,
            $shortCut,
            $message
        );
    }

    /**
     * check report conditions
     * @param UserInterface $author
     * @param T $target
     * @return void
     */
    public function checkReportConditions(UserInterface $author, $target)
    {
        // TODO 중복 신고 ON/OFF 기능 추가 후 적용
        /*if (app('xe.claim.handler')->exists($target, $author) === true) {
            throw new AlreadyClaimedException();
        }*/
    }
}
