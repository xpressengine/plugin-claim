<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\Handler as ClaimHandler;

/**
 * Class AbstractClaimType
 * @package Xpressengine\Plugins\Claim\Types
 * @template T
 */
abstract class AbstractClaimType
{
    /**
     * claim type
     * @var string
     */
    protected $name;

    /**
     * text of claim type
     * @var string
     */
    protected $text;

    /**
     * class of claim type
     * @var string
     */
    protected $class;

    /**
     * get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * get text
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * get class
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

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
    public function report(UserInterface $author, string $targetId, string $shortCut, string $message = '')
    {
        app('xe.claim.handler')->add($this->name, $targetId, $author, $shortCut, $message);
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
        /*if (app('xe.claim.handler')->has($target, $author) === true) {
            throw new AlreadyClaimedException();
        }*/
    }
}
