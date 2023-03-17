<?php

namespace Xpressengine\Plugins\Claim\Factory\Types;

use Xpressengine\User\UserInterface;
use Xpressengine\Plugins\Claim\ClaimHandler;

/**
 * Class AbstractClaimType
 * @package Xpressengine\Plugins\Claim\Factory\Types
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
     * @var ClaimHandler
     */
    private $handler;

    public function __construct(ClaimHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param UserInterface $author
     * @param string $targetId
     * @return bool
     */
    public function exists(UserInterface $author, string $targetId)
    {
        return $this->handler->exists($this->name, $author, $targetId);
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
        $this->handler->report(
            $this->name,
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
    public function checkReportConditions(UserInterface $author, $target) { }
}
