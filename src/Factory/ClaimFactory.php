<?php

namespace Xpressengine\Plugins\Claim\Factory;

use Xpressengine\Plugins\Claim\ClaimHandler;
use Xpressengine\Plugins\Claim\Exceptions\NotSupportClaimTypeException;
use Xpressengine\Plugins\Claim\Factory\Types;
use Xpressengine\Plugins\Claim\Factory\Types\AbstractClaimType;

class ClaimFactory
{
    /**
     * @var array<string, AbstractClaimType>
     */
    private $activateClaimTypes = [];

    /**
     * default claim types
     * @var string[]
     */
    protected $defaultClaimTypes = [
        Types\UserClaim::class,
        Types\BoardClaim::class,
        Types\CommentClaim::class
    ];

    public function __construct(ClaimHandler $handler)
    {
        foreach ($this->defaultClaimTypes as $class) {
            $targetClaimType = app($class, ['handler' => $handler]);
            assert($targetClaimType instanceof Types\AbstractClaimType);
            $this->register($targetClaimType);
        }
    }

    /**
     * @param AbstractClaimType $type
     * @return void
     */
    public function register(AbstractClaimType $type)
    {
        if (!array_key_exists($type->getName(), $this->activateClaimTypes) && class_exists($type->getClass())) {
            $this->activateClaimTypes[$type->getName()] = $type;
        }
    }

    /**
     * @param string $name
     * @return void
     */
    public function remove(string $name)
    {
        if (array_key_exists($name, $this->activateClaimTypes)) {
            unset($this->activateClaimTypes[$name]);
        }
    }

    /**
     * @param string $name
     * @return AbstractClaimType
     */
    public function make(string $name)
    {
        $targetType = array_get($this->activateClaimTypes, $name);
        if ($targetType === null) {
            throw new NotSupportClaimTypeException();
        }

        return $targetType;
    }

    /**
     * @return array<string, AbstractClaimType>
     */
    public function getActivateTypes()
    {
        return $this->activateClaimTypes;
    }
}
