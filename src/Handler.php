<?php

namespace Xpressengine\Plugins\Claim;

use App\Facades\XeDB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Xpressengine\User\UserInterface;
use Xpressengine\Config\ConfigManager;
use Xpressengine\Plugins\Claim\Models\ClaimLog;
use Xpressengine\Plugins\Claim\Types\AbstractClaimType;
use Xpressengine\Plugins\Claim\Repositories\ClaimRepository;
use Xpressengine\Plugins\Claim\Exceptions\ClaimException;
use Xpressengine\Plugins\Claim\Exceptions\NotFoundClaimTargetException;
use Xpressengine\Plugins\Claim\Exceptions\NotSupportClaimTypeException;

class Handler
{
    /** @var ClaimRepository */
    protected $repository;

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
     * @param ClaimRepository $repository
     * @param ConfigManager $configManager
     * @param array $defaultClaimTypeClasses
     */
    public function __construct(
        ClaimRepository $repository,
        ConfigManager $configManager,
        array $defaultClaimTypeClasses
    ) {
        $this->repository = $repository;
        $this->configManager = $configManager;
        $this->activateClaimTypes = [];

        foreach ($defaultClaimTypeClasses as $class) {
            $targetClaimType = app($class);
            assert($targetClaimType instanceof Types\AbstractClaimType);
            $this->registerClaimType($targetClaimType);
        }
    }

    /**
     * claim type 등록
     * @param AbstractClaimType $claimType
     * @return void
     */
    public function registerClaimType(AbstractClaimType $claimType)
    {
        if (!array_key_exists($claimType->getName(), $this->activateClaimTypes) && class_exists($claimType->getClass())) {
            $this->activateClaimTypes[$claimType->getName()] = $claimType;
        }
    }

    /**
     * 활성화된 claim types 를 반환
     * @return array|AbstractClaimType[]
     */
    public function getActivateClaimTypes()
    {
        return $this->activateClaimTypes;
    }

    /**
     * 해당 ID의 신고 로그를 반환
     * @param string $id
     * @return ClaimLog
     */
    public function findLogOrFail(string $id)
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * 해당 컨텐츠가 신고된 횟수를 반환
     * @param string $claimType
     * @param string $targetId
     * @return int
     */
    public function count(string $claimType, string $targetId)
    {
        return $this->repository->where('target_id', $targetId)
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
     * @param string $id
     * @return bool|null
     * @throws \Exception
     */
    public function removeLog(string $id)
    {
        return $this->findLogOrFail($id)->delete();
    }

    /**
     * 신고 로그 삭제
     * @param string $claimType
     * @param string $targetId
     * @param UserInterface $author
     * @return mixed
     */
    public function removeLogByTargetId(string $claimType, string $targetId, UserInterface $author)
    {
        return $this->repository->where('user_id', $author->getKey())
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
            $handler = $claimType->getHandler();
            $handler->report($author, $targetId, $shortCut, $message);
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
     * @param string $targetUserId
     * @param UserInterface $author
     * @param string $shortCut
     * @param string $message
     * @return ClaimLog
     */
    public function addLog(
        string $claimType,
        string $targetId,
        string $targetUserId,
        UserInterface $author,
        string $shortCut,
        string $message = ''
    ) {
        if ($this->exists($claimType, $targetId, $author) === true) {
            throw new Exceptions\AlreadyClaimedException;
        }

        return $this->repository->create([
            'claim_type' => $claimType,
            'short_cut' => $shortCut,
            'target_id' => $targetId,
            'target_user_id' => $targetUserId,
            'user_id' => $author->getKey(),
            'message' => $message,
            'status' => ClaimLog::STATUS_NEW,
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
    public function exists(string $claimType, string $targetId, UserInterface $author)
    {
        return $this->repository->where('user_id', $author->getKey())
            ->where('target_id', $targetId)
            ->where('claim_type', $claimType)
            ->exists();
    }

    /**
     * 신고 로그 수정
     * @param string $id
     * @param string $status
     * @param string $adminMessage
     * @return ClaimLog
     */
    public function updateLog(string $id, string $status, string $adminMessage = '')
    {
        $log = $this->findLogOrFail($id);

        if (!array_key_exists($status, ClaimLog::STATUSES)) {
            throw new \UnexpectedValueException('Invalid Claim status');
        }

        $log->status = $status;
        $log->admin_message = $adminMessage;
        $log->save();

        return $log;
    }

    /**
     * 신고 로그 페이지네이션 반환
     * @param array $inputs
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateClaimLogs(array $inputs = [])
    {
        $query = $this->repository->query();
        $this->applyFiltersToClaimLogs($query, $inputs);
        $logs = $query->paginate();

        $logs->setCollection($logs->getCollection()->map(function ($log) {
            $log->claim_type_text = xe_trans($this->getClaimTypeByKey($log->claim_type)->getText());
            return $log;
        }));

        return $logs;
    }

    /**
     * 신고 로그 검색 필터 적용
     * @param Builder $query
     * @param array $inputs
     * @return Builder
     */
    protected function applyFiltersToClaimLogs(Builder $query, array $inputs = [])
    {
        $claimType = array_get($inputs, 'claim_type');
        if ($claimType !== null) {
            $query->where('claim_type', $claimType);
        }

        $claimStatus = array_get($inputs, 'claim_status');
        if ($claimStatus !== null) {
            $query->where('status', $claimStatus);
        }

        $keyField = array_get($inputs, 'keyfield');
        $keyword = array_get($inputs, 'keyword');
        if ($keyField !== null && $keyword !== null) {
            if ($keyField === 'author_name') {
                $query->whereHas('user', function ($query) use ($keyword) {
                    $query->where('display_name', 'like', "%{$keyword}%");
                });
            }

            else if ($keyField === 'target_name') {
                $query->whereHas('targetUser', function ($query) use ($keyword) {
                    $query->where('display_name', 'like', "%{$keyword}%");
                });
            }

            else if ($keyField === 'message') {
                $query->where('message', 'like', "%{$keyword}%");
            }
        }

        $startDate = array_get($inputs, 'start_date');
        $endDate = array_get($inputs, 'end_date');
        if ($startDate !== null || $endDate !== null) {
            $query->whereBetween('created_at', [
                $startDate ? $startDate . ' 00:00:00' : Carbon::minValue(),
                $endDate ? $endDate . ' 23:59:59' : Carbon::maxValue()
            ]);
        }

        $query->orderByDesc('created_at');

        return $query;
    }
}
