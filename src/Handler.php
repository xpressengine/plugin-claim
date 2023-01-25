<?php
/**
 * Handler.php
 *
 * This file is part of the Xpressengine package.
 *
 * PHP version 7
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Claim;

use Xpressengine\User\UserInterface;
use Xpressengine\Config\ConfigManager;
use Xpressengine\Plugins\Claim\Models\ClaimLog;
use Xpressengine\Plugins\Claim\Types\AbstractClaimType;
use Xpressengine\Plugins\Claim\Exceptions\NotSupportClaimTypeException;

/**
 * Handler
 *
 * @category    Claim
 * @package     Xpressengine\Plugins\Claim
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class Handler
{
    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * @var array<string, AbstractClaimType>
     */
    protected $activateClaimTypes = [];

    /**
     * @var string
     */
    protected $claimType;

    /**
     * @var string
     */
    const CONFIG_NAME = 'Claim';

    /**
     * @param ConfigManager $configManager
     * @param array $activateClaimTypes
     */
    public function __construct(ConfigManager $configManager, array $activateClaimTypes)
    {
        $this->configManager = $configManager;
        $this->activateClaimTypes = $activateClaimTypes;
    }

    /**
     * claim 에서 사용 할 type name 설정
     * @param string $claimType
     * @return void
     */
    public function set(string $claimType)
    {
        if (!array_key_exists($claimType, $this->activateClaimTypes)) {
            throw new NotSupportClaimTypeException();
        }

        $this->claimType = $claimType;
    }

    /**
     * 신고 수
     * @param string $targetId
     * @return int
     */
    public function count(string $targetId)
    {
        return ClaimLog::where('target_id', $targetId)->where('claim_type', $this->claimType)->count();
    }

    /**
     * 신고 로그 삭제
     * @param int $id id
     * @return void
     */
    public function remove(int $id)
    {
        ClaimLog::find($id)->delete();
    }

    /**
     * 신고 로그 삭제
     * @param string $targetId
     * @param UserInterface $author
     * @return void
     */
    public function removeByTargetId(string $targetId, UserInterface $author)
    {
        ClaimLog::where('user_id', $author->getId())->where('target_id', $targetId)
            ->where('claim_type', $this->claimType)->delete();
    }

    /**
     * 대상을 신고
     * @param string $targetId
     * @param UserInterface $author
     * @param string $shortCut
     * @param string $message
     * @return void
     */
    public function report(string $targetId, UserInterface $author, string $shortCut, string $message = '')
    {
        if (!array_key_exists($this->claimType, $this->activateClaimTypes)) {
            throw new NotSupportClaimTypeException();
        }

        $claimType = $this->activateClaimTypes[$this->claimType];
        $claimType->report($this, $author, $targetId, $shortCut, $message);
    }

    /**
     * 신고 로그 등록
     * @param string $targetId
     * @param UserInterface $author
     * @param string $shortCut
     * @param string $message
     * @return ClaimLog
     */
    public function add(string $targetId, UserInterface $author, string $shortCut, string $message = '')
    {
        if ($this->has($targetId, $author) === true) {
            throw new Exceptions\AlreadyClaimedException;
        }

        return ClaimLog::create([
            'claim_type' => $this->claimType,
            'short_cut' => $shortCut,
            'target_id' => $targetId,
            'user_id' => $author->getId(),
            'message' => $message,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ]);
    }

    /**
     * 신고 여부
     * @param string $targetId
     * @param UserInterface $author
     * @return bool
     */
    public function has(string $targetId, UserInterface $author)
    {
        return ClaimLog::where('user_id', $author->getId())->where('target_id', $targetId)
                ->where('claim_type', $this->claimType)->first() !== null;
    }
}
