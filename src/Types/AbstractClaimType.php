<?php

namespace Xpressengine\Plugins\Claim\Types;

use Xpressengine\Plugins\Claim\Handlers\AbstractClaimTypeHandler;

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
     * @var AbstractClaimTypeHandler
     */
    protected $handler;

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
     * get target class
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * get target claim type handler
     * @return AbstractClaimTypeHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
