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
    public function report(
        ClaimHandler $handler,
        UserInterface $author,
        string $targetId,
        string $shortCut,
        string $message = ''
    ) {
        $handler->add($targetId, $author, $shortCut, $message);
    }

    /**
     * check report conditions
     * @param ClaimHandler $handler
     * @param UserInterface $author
     * @param T $target
     * @return void
     */
    public function checkReportConditions(
        ClaimHandler $handler,
        UserInterface $author,
        $target
    ) {
        // TODO 중복 신고 ON/OFF 기능 추가 후 적용
        /*if ($handler->has($target, $author) === true) {
            throw new AlreadyClaimedException();
        }*/
    }
}
