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

use App\Facades\XeDB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Xpressengine\Plugins\Claim\Exceptions\ClaimException;
use Xpressengine\Plugins\Claim\Exceptions\NotFoundClaimTargetException;
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
     * claim type 등록
     * @param AbstractClaimType $claimType
     * @return void
     */
    public function registerClaimType(AbstractClaimType $claimType)
    {
        if (!array_key_exists($claimType->getName(), $this->activateClaimTypes)) {
            $this->activateClaimTypes[$claimType->getName()] = $claimType;
        }
    }

    /**
     * 해당 컨텐츠가 신고된 횟수를 반환
     * @param string $claimType
     * @param string $targetId
     * @return int
     */
    public function count(string $claimType, string $targetId)
    {
        return ClaimLog::where('target_id', $targetId)
            ->where('claim_type', $claimType)
            ->count();
    }

    /**
     * 해당 키의 claim type을 반환
     * @param string $claimType
     * @return AbstractClaimType
     */
    public function getClaimTypeByKey(string $claimType)
    {
        if (!array_key_exists($claimType, $this->activateClaimTypes)) {
            throw new NotSupportClaimTypeException();
        }

        return $this->activateClaimTypes[$claimType];
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
     * @param string $claimType
     * @param string $targetId
     * @param UserInterface $author
     * @return void
     */
    public function removeByTargetId(string $claimType, string $targetId, UserInterface $author)
    {
        ClaimLog::where('user_id', $author->getKey())
            ->where('target_id', $targetId)
            ->where('claim_type', $claimType)
            ->delete();
    }

    /**
     * 대상을 신고
     * @param string $claimType
     * @param string $targetId
     * @param UserInterface $author
     * @param string $shortCut
     * @param string $message
     * @return void
     */
    public function report(
        string $claimType,
        string $targetId,
        UserInterface $author,
        string $shortCut,
        string $message = ''
    ) {
        XeDB::beginTransaction();

        try {
            $claimType = $this->getClaimTypeByKey($claimType);
            $claimType->report($author, $targetId, $shortCut, $message);
        } catch (ClaimException $e) {
            XeDB::rollback();
            throw $e;
        } catch (ModelNotFoundException $e) {
            XeDB::rollback();
            throw new NotFoundClaimTargetException();
        } catch (\Exception $e) {
            XeDB::rollback();
            Log::error($e);
            throw new ClaimException();
        }

        XeDB::commit();
    }

    /**
     * 신고 로그 등록
     * @param string $claimType
     * @param string $targetId
     * @param UserInterface $author
     * @param string $shortCut
     * @param string $message
     * @return ClaimLog
     */
    public function add(
        string $claimType,
        string $targetId,
        UserInterface $author,
        string $shortCut,
        string $message = ''
    ) {
        if ($this->has($claimType, $targetId, $author) === true) {
            throw new Exceptions\AlreadyClaimedException;
        }

        return ClaimLog::create([
            'claim_type' => $claimType,
            'short_cut' => $shortCut,
            'target_id' => $targetId,
            'user_id' => $author->getKey(),
            'message' => $message,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ]);
    }

    /**
     * 신고 여부
     * @param string $claimType
     * @param string $targetId
     * @param UserInterface $author
     * @return bool
     */
    public function has(string $claimType, string $targetId, UserInterface $author)
    {
        return ClaimLog::where('user_id', $author->getKey())
            ->where('target_id', $targetId)
            ->where('claim_type', $claimType)
            ->exists();
    }
}
