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

use Xpressengine\Counter\Counter;
use Xpressengine\Config\ConfigManager;
use Xpressengine\Plugins\Claim\Models\ClaimLog;
use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;

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
     * @var string
     */
    protected $claimType;

    /**
     * @var string
     */
    const CONFIG_NAME = 'Claim';

    /**
     * create instance
     *
     * @param ConfigManager $configManager config manager
     */
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * claim 에서 사용 할 type name 설정
     *
     * @param string $claimType claim type
     *
     * @return void
     */
    public function set($claimType)
    {
        $this->claimType = $claimType;
    }

    /**
     * 신고 수
     *
     * @param string $targetId targetId
     *
     * @return int
     */
    public function count($targetId)
    {
        return ClaimLog::where('target_id', $targetId)->where('claim_type', $this->claimType)->count();
    }

    /**
     * 신고 삭제
     *
     * @param int $id id
     *
     * @return void
     */
    public function remove($id)
    {
        ClaimLog::find($id)->delete();
    }

    /**
     * 신고 삭제
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     *
     * @return void
     */
    public function removeByTargetId($targetId, UserInterface $author)
    {
        ClaimLog::where('user_id', $author->getId())->where('target_id', $targetId)
            ->where('claim_type', $this->claimType)->delete();
    }

    /**
     * 신고 추가
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     * @param string        $shortCut 바로가기
     *
     * @return void
     */
    public function add($targetId, UserInterface $author, $shortCut)
    {
        if ($this->has($targetId, $author) === true) {
            throw new Exceptions\AlreadyClaimedException;
        }

        ClaimLog::create([
            'claim_type' => $this->claimType,
            'short_cut' => $shortCut,
            'target_id' => $targetId,
            'user_id' => $author->getId(),
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ]);
    }

    /**
     * 신고 여부
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     *
     * @return bool
     */
    public function has($targetId, UserInterface $author)
    {
        return ClaimLog::where('user_id', $author->getId())->where('target_id', $targetId)
                ->where('claim_type', $this->claimType)->first() !== null;
    }
}
