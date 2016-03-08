<?php
/**
 * Claim handler
 *
 * PHP version 5
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Claim;

use Xpressengine\Counter\Counter;
use Xpressengine\Config\ConfigManager;
use Xpressengine\Plugins\Claim\Models\ClaimLog;
use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;


/**
 * Claim handler
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
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
     * @param ConfigManager   $configManager config manager
     */
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * claim 에서 사용 할 type name 설정
     *
     * @param string $claimType claim type
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
     * @return int
     */
    public function count($targetId)
    {
        return ClaimLog::where('targetId', $targetId)->where('claimType', $this->claimType)->count();
    }

    /**
     * 신고 추가
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     * @param string        $shortCut 바로가기
     * @return void
     */
    public function add($targetId, UserInterface $author, $shortCut)
    {
        if ($this->invoked($targetId, $author) === true) {
            throw new Exceptions\InvokedException;
        }

        ClaimLog::create([
            'claimType' => $this->claimType,
            'shortCut' => $shortCut,
            'targetId' => $targetId,
            'userId' => $author->getId(),
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ]);
    }

    /**
     * 신고 삭제
     *
     * @param int $id id
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
     * @return void
     */
    public function removeByTargetId($targetId, UserInterface $author)
    {
        ClaimLog::where('userId', $author->getId())->where('targetId', $targetId)
            ->where('claimType', $this->claimType)->delete();
    }

    /**
     * 신고 여부
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     * @return bool
     */
    public function invoked($targetId, UserInterface $author)
    {
        return ClaimLog::where('userId', $author->getId())->where('targetId', $targetId)
            ->where('claimType', $this->claimType)->first() !== null;
    }
}
